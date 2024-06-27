<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Instrument;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InstrumentController extends Controller
{
    public function index()
    {
        $data['instruments'] = Instrument::where('deleted_at', null)->get();

        return view('instrument.index', $data);
    }

    public function store(Request $request)
    {
        $instrument = Instrument::create($request->all());

        $instrument->generateBarcode();

        Alert::success('Berhasil', 'Berhasil register instrument');

        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        $instrument = Instrument::find($id);

        $instrument->update($request->all());

        Alert::success('Berhasil', 'Berhasil mengubah instrument');

        return redirect()->back();
    }

    public function delete($id)
    {
        $instrument = Instrument::find($id);

        $instrument->update([
            'deleted_at' => Carbon::now()
        ]);

        Alert::success('Berhasil', 'Berhasil menghapus instrument');

        return redirect()->back();
    }
}
