<?php

namespace App\Http\Controllers;

use App\Models\Analytic;
use App\Models\Instrument;
use App\Models\Sample;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Alert;
use App\Helpers\CreateLog;
use App\Models\ParameterTesting;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnalyticController extends Controller
{
    public function index()
    {
        $uniqueSamples = Analytic::select('sample_id', DB::raw('MIN(id) as id'))
            ->where('type', 'sample')
            ->groupBy('sample_id', 'replication')
            ->get();

        $data['analytics'] = Analytic::whereIn('id', $uniqueSamples->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->get();

        $data['inreview_sample'] = Analytic::where('status', 'On Process')->where('type', 'sample')->count();
        $data['done_sample'] = Analytic::where('status', 'Done')->where('type', 'sample')->count();

        return view('analisa.index', $data);
    }

    public function showSample(Request $request, $qrcode)
    {
        $replication = $request->replication;
        $sampleId = $request->sampleId;
        $instrumentId = $request->instrumentId;

        if ($request->method == 'scan_in') {
            $sample = Sample::where('qr_code', $qrcode)
                ->with(['ParameterTesting', 'TypeTesting'])
                ->with(['Analytics' => function ($q) {
                    $q->with('Instrument');
                }])
                ->first();
        } else {
            $sample = Sample::where('qr_code', $qrcode)
                ->with(['ParameterTesting', 'TypeTesting'])
                ->with(['Analytics' => function ($q) use ($replication, $sampleId, $instrumentId) {
                    $q->where('replication', $replication);
                    $q->where('sample_id', $sampleId);
                    $q->where('instrument_id', $instrumentId);
                    $q->with('Instrument');
                    $q->orderBy('id', 'asc');
                    $q->limit(1);
                }])
                ->first();
        }

        return response()->json([
            'data' => $sample
        ]);
    }

    public function showInstrument($qrcode)
    {
        $instrument = Instrument::where('qr_code', $qrcode)->first();

        return response()->json([
            'data' => $instrument
        ]);
    }

    public function store(Request $request)
    {
        $instrument = Instrument::find($request->id_instrument);
        $sample = Sample::find($request->id_sample);
        $analyticF = Analytic::where('sample_id', $sample->id)->where('instrument_id', $instrument->id)
            ->where('status', 'On Process')
            ->first();

        try {
            DB::beginTransaction();

            $analytic = new Analytic();
            $analytic->type = 'sample';
            $analytic->sample_id = $request->id_sample;
            $analytic->instrument_id = $request->id_instrument;
            $analytic->pic = Auth::user()->id;
            $analytic->temperature = $request->temperature;
            $analytic->method = $request->method;
            $analytic->replication = $request->replication ? $request->replication : $analyticF->replication;

            if ($request->leadtimeType == 'jam') {
                $leadtime = Carbon::now()->addHour($request->leadtime);
            } else {
                $leadtime = Carbon::now()->addDay($request->leadtime);
            }
            $analytic->leadtime = $leadtime;

            $parameter = ParameterTesting::find($sample->parameter_testing_id);

            if ($request->scan_type == 'scan_in') {
                if ($instrument->tanggal_rekalibrasi < Carbon::now()->format('Y-m-d')) {
                    Alert::info('info', 'Please recalibrate instrument');
                    return redirect()->back();
                }
                $analytic->scan_in = Carbon::now();
                $analytic->status = 'On Process';

                $sample->status = 'On Process';
                $sample->tenggat_testing = Carbon::now()->addDay($parameter->leadtime);
                CreateLog::createLog(Auth::user()->name . ' scan in sample ' . $sample->no_sample . '-' . $analytic->replication . '-' . $instrument->nama_instrument);
            } elseif ($request->scan_type == 'scan_out') {
                if ($sample->parameter_testing_id == 11) {
                    return redirect()->route('addMicroba', [$request->id_sample, $request->id_instrument]);
                }
                $analytic->scan_out = Carbon::now();
                $analytic->status = 'On Process';
                $sample->status = 'On Process';
                CreateLog::createLog(Auth::user()->name . ' scan out sample ' . $sample->no_sample . ' ' . $analytic->replication  . '-' . $instrument->nama_instrument);
            } elseif ($request->scan_type == 'scan_done') {
                $analytic->scan_done = Carbon::now();
                $analytic->status = 'Done';
                $sample->status = 'Done';
                CreateLog::createLog(Auth::user()->name . ' scan done sample ' . $sample->no_sample . ' ' . $analytic->replication  . '-' . $instrument->nama_instrument);
            }

            $analytic->save();
            $sample->save();

            DB::commit();
            Alert::success('success', 'Success Scan Sample!');
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function addMicrobaToSample($sampleId, $instrumentId)
    {
        $data['sample'] = Sample::find($sampleId);
        $data['instrument_id'] = $instrumentId;
        $data['parameters'] = ParameterTesting::where('deleted_at', null)
            ->where('is_microba', true)
            ->get();

        return view('analisa.add_microba', $data);
    }

    public function submitMicrobaToSample(Request $request, $sampleId, $instrumentId)
    {
        $sample = Sample::find($sampleId);

        try {
            DB::beginTransaction();

            $analytic = new Analytic();
            $analytic->type = 'sample';
            $analytic->sample_id = $sample->id;
            $analytic->instrument_id = $instrumentId;
            $analytic->pic = Auth::user()->id;
            $analytic->temperature = $sample->Analytics[0]->temperature;
            $analytic->method = $request->method;
            $analytic->leadtime = $request->leadtime;
            $analytic->scan_done = Carbon::now();
            $analytic->status = 'Done';
            $analytic->replication = $sample->Analytics[0]->replication;
            $analytic->save();

            foreach ($request->parameter_testing_id as $item) {
                $parameter = ParameterTesting::find($item);
                $createSample = Sample::create([
                    'type_id' => $sample->type_id,
                    'no_sample' => $sample->no_sample,
                    'no_batch' => $sample->no_batch,
                    'deskripsi_sample' => $sample->deskripsi_sample,
                    'tanggal_terima' => $sample->tanggal_terima,
                    'parameter_testing_id' => $item,
                    'tenggat_testing' => Carbon::now()->addDay($parameter->leadtime),
                    'jumlah_sampel' => $sample->jumlah_sampel,
                    'pic' => Auth::user()->id,
                    'status' => 'Pending'
                ]);
                $createSample->generateBarcode();
            }

            $sample->status = 'Done';
            $sample->save();

            CreateLog::createLog(Auth::user()->name . ' scan done sample ' . $sample->no_sample . ' ' . $analytic->replication);

            DB::commit();

            Alert::success('Berhasil', 'Berhasil menambahkan sample');

            return redirect()->route('analitics');
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
            Alert::error('Gagal', 'Gagal menambahkan sample ' . $e);

            return redirect()->back();
        }
    }

    public function replication(Request $request)
    {
        $replications = Analytic::where('replication', 'LIKE', '%' . $request->query('q') . '%')
            ->where('type', $request->type)
            ->where('sample_id', $request->sampleID)
            ->where('instrument_id', $request->instrumentID)
            ->orderBy('id', 'desc')
            ->limit(1)
            ->pluck('replication');

        return response()->json($replications);
    }
}
