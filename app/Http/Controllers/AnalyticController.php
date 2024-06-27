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
        $data['analytics'] = Sample::where('status', 'In Review')->get();

        return view('analisa.index', $data);
    }

    public function showSample($qrcode)
    {
        $sample = Sample::where('qr_code', $qrcode)
            ->with('ParameterTesting')
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

        if ($instrument->tanggal_rekalibrasi < Carbon::now()->format('Y-m-d')) {
            Alert::info('info', 'Please recalibrate instrument');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $analytic = Analytic::create([
                'sample_id' => $request->id_sample,
                'instrument_id' => $request->id_instrument,
                'pic' => Auth::user()->id,
                'status' => 'In Review'
            ]);

            $parameter = ParameterTesting::find($sample->parameter_testing_id);

            if ($request->scan_type == 'scan_in') {
                $analytic->scan_in = Carbon::now();
                $sample->tenggat_testing = Carbon::now()->addDay($parameter->leadtime);
            } else {
                $analytic->scan_out = Carbon::now();
            }
            $analytic->save();

            $sample->status = 'In Review';
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
