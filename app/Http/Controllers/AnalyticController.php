<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Sample;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function index()
    {
        return view('analisa.index');
    }

    public function showSample($qrcode)
    {
        $sample = Sample::where('qr_code', $qrcode)
            ->with('ParameterTesting')
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
        dd($request);
    }
}
