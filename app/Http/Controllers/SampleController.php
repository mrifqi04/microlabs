<?php

namespace App\Http\Controllers;

use App\Models\ParameterTesting;
use Illuminate\Http\Request;
use Throwable;
use Alert;
use App\Models\Sample;
use App\Models\TypeTesting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SampleController extends Controller
{
    public function index()
    {
        $data['parameters'] = ParameterTesting::where('deleted_at', null)->get();
        $data['samples'] = Sample::where('deleted_at', null)->get();
        $data['types'] = TypeTesting::all();

        return view('sample.index', $data);
    }

    public function store(Request $request)
    {
        $parameter = ParameterTesting::find($request->parameter_uji);
        $samples = Sample::where('deleted_at', null)->get();
        $type = TypeTesting::where('type_code', $request->type)->first();

        try {
            DB::beginTransaction();

            $createSample = Sample::create([
                'type_id' => $type->id,
                'no_sample' => $request->section1 . '-' . $request->section2 . $request->section3 . str_pad(count($samples) + 1, 4, 0, STR_PAD_LEFT),
                'no_batch' => $request->no_batch,
                'deskripsi_sample' => $request->deskripsi_sample,
                'tanggal_terima' => $request->tanggal_terima,
                'parameter_testing_id' => $request->parameter_uji,
                'tenggat_testing' => Carbon::now()->addDay($parameter->leadtime),
                'jumlah_sampel' => $request->type == 'EM' ? $request->jumlah_sampel : 1,
                'pic' => Auth::user()->id,
                'status' => 'Pending'
            ]);

            $createSample->generateBarcode();

            DB::commit();

            Alert::success('Berhasil', 'Berhasil menambahkan sample');

            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
            Alert::error('Gagal', 'Gagal menambahkan sample ' . $e);

            return redirect()->back();
        }
    }

    public function update($id, Request $request)
    {
        $sample = Sample::find($id);
        $sample->update($request->all());
        $sample->no_sample = $request->section1 . '-' . $request->section2 . '-' . $request->section3 . '-' . $request->section4;
        $sample->save();

        Alert::success('Berhasil', 'Berhasil update sample');

        return redirect()->back();
    }

    public function delete($id)
    {
        $sample = Sample::find($id);

        $sample->update([
            'deleted_at' => Carbon::now()
        ]);

        Alert::success('Berhasil', 'Berhasil menghapus sample');

        return redirect()->back();
    }

    public function countSample($id)
    {
        $samples = Sample::where('type_id', $id)->count();

        return response()->json($samples);
    }
}
