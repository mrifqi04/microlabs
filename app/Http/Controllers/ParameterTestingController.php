<?php

namespace App\Http\Controllers;

use App\Models\ParameterTesting;
use Carbon\Carbon;
use Alert;
use Illuminate\Http\Request;

class ParameterTestingController extends Controller
{
    public function index()
    {
        $data['parameters'] = ParameterTesting::where('deleted_at', null)->get();

        return view('ParameterTesting.index', $data);
    }

    public function store(Request $request)
    {
        ParameterTesting::create($request->all());

        Alert::success('Berhasil', 'Berhasil menambahkan parameter');

        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        $parameter = ParameterTesting::find($id);

        $parameter->update($request->all());

        Alert::success('Berhasil', 'Berhasil update parameter');

        return redirect()->back();
    }

    public function delete($id)
    {
        $parameter = ParameterTesting::find($id);

        $parameter->update([
            'deleted_at' => Carbon::now()
        ]);

        Alert::success('Berhasil', 'Berhasil menghapus parameter');

        return redirect()->back();
    }
}
