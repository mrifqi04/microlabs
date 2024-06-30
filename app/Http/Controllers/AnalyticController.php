<?php

namespace App\Http\Controllers;

use App\Models\Analytic;
use App\Models\Instrument;
use App\Models\Sample;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Alert;
use App\Models\ParameterTesting;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnalyticController extends Controller
{
    public function index()
    {
        $data['analytics'] = Sample::where('status', '!=', 'Pending')->get();
        $data['inreview_sample'] = Sample::where('status', 'In Review')->count();
        $data['done_sample'] = Sample::where('status', 'Done')->count();

        return view('analisa.index', $data);
    }

    public function showSample($qrcode)
    {
        $sample = Sample::where('qr_code', $qrcode)
            ->with(['ParameterTesting', 'TypeTesting'])
            ->with('Analytics', function ($q) {
                $q->orderBy('id', 'desc');
                $q->with('Instrument');
                $q->limit(1);
            })
            ->first();

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

        try {
            DB::beginTransaction();

            $analytic = new Analytic();
            $analytic->sample_id = $request->id_sample;
            $analytic->instrument_id = $request->id_instrument;
            $analytic->pic = Auth::user()->id;

            $parameter = ParameterTesting::find($sample->parameter_testing_id);

            if ($request->scan_type == 'scan_in') {
                if ($instrument->tanggal_rekalibrasi < Carbon::now()->format('Y-m-d')) {
                    Alert::info('info', 'Please recalibrate instrument');
                    return redirect()->back();
                }
                $analytic->scan_in = Carbon::now();
                $analytic->status = 'In Review';

                $sample->status = 'In Review';
                $sample->tenggat_testing = Carbon::now()->addDay($parameter->leadtime);
            } elseif ($request->scan_type == 'scan_out') {
                $analytic->scan_out = Carbon::now();
                $analytic->status = 'In Review';
                $sample->status = 'In Review';
            } elseif ($request->scan_type == 'scan_done') {
                $analytic->scan_done = Carbon::now();
                $analytic->status = 'Done';
                $sample->status = 'Done';
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
}
