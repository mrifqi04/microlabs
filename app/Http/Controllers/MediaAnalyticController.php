<?php

namespace App\Http\Controllers;

use App\Helpers\CreateLog;
use App\Models\Analytic;
use App\Models\Instrument;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MediaAnalyticController extends Controller
{
    public function index()
    {
        $uniqueSamples = Analytic::select('sample_id', DB::raw('MIN(id) as id'))
            ->where('type', 'media')
            ->groupBy('sample_id', 'replication')
            ->get();

        $data['analytics'] = Analytic::whereIn('id', $uniqueSamples->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->get();

        $data['inreview_media'] = Analytic::where('status', 'On Process')->where('type', 'media')->count();
        $data['done_media'] = Analytic::where('status', 'Done')->where('type', 'media')->count();

        return view('analisa.analisa-media', $data);
    }

    public function showMedia(Request $request, $qrcode)
    {
        $replication = $request->replication;
        $sampleId = $request->sampleId;
        $instrumentId = $request->instrumentId;

        if ($request->method == 'scan_in') {
            $media = Media::where('barcode', $qrcode)
                ->with(['Analytics' => function ($q) {
                    $q->with('Instrument');
                }])
                ->first();
        } else {
            $media = Media::where('barcode', $qrcode)
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
            'data' => $media
        ]);
    }

    public function store(Request $request)
    {
        $instrument = Instrument::find($request->id_instrument);
        $sample = Media::find($request->id_sample);
        $analyticF = Analytic::where('sample_id', $sample->id)->where('instrument_id', $instrument->id)
            ->where('status', 'On Process')
            ->first();

        try {
            DB::beginTransaction();

            $analytic = new Analytic();
            $analytic->type = 'media';
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

            if ($request->scan_type == 'scan_in') {
                if ($instrument->tanggal_rekalibrasi < Carbon::now()->format('Y-m-d')) {
                    Alert::info('info', 'Please recalibrate instrument');
                    return redirect()->back();
                }
                $analytic->scan_in = Carbon::now();
                $analytic->status = 'On Process';

                $sample->status = 'On Process';
            } elseif ($request->scan_type == 'scan_out') {
                $analytic->scan_out = Carbon::now();
                $analytic->status = 'On Process';
                $sample->status = 'On Process';
            } elseif ($request->scan_type == 'scan_done') {
                $analytic->scan_done = Carbon::now();
                $analytic->status = 'Done';
                $sample->status = 'Done';
            }

            $analytic->save();
            $sample->save();

            CreateLog::createLog(Auth::user()->name . ' scan in media ' . $sample->media_name);

            DB::commit();

            Alert::success('success', 'Success Scan Sample!');

            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollback();
            dd($e);
        }
    }
}
