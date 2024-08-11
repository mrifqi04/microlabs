<?php

namespace App\Http\Controllers;

use App\Helpers\CreateLog;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MediaController extends Controller
{
    public function index()
    {
        $data['medias'] = Media::where('deleted_at', null)->get();

        return view('media.index', $data);
    }

    public function store(Request $request)
    {
        $createMedia = Media::create($request->all());

        $createMedia->generateBarcode();
        $createMedia->pic = Auth::user()->id;
        $createMedia->save();

        CreateLog::createLog(Auth::user()->name . ' create media ' . $createMedia->media_name);

        Alert::success('Berhasil', 'Berhasil membuat media');

        return redirect()->back();
    }

    public function generateBarcode($id)
    {
        $data['media'] = Media::find($id);

        return view('media.generate_barcode', $data);
    }

    public function update(Request $request, $id)
    {
        $media = Media::find($id);
        $media->update($request->all());

        CreateLog::createLog(Auth::user()->name . ' update media ' . $media->media_name);

        Alert::success('Berhasil', 'Berhasil update sample');

        return redirect()->back();
    }

    public function delete($id)
    {
        $media = Media::find($id);

        $media->update([
            'deleted_at' => Carbon::now()
        ]);

        CreateLog::createLog(Auth::user()->name . ' delete media ' . $media->media_name);

        Alert::success('Berhasil', 'Berhasil menghapus media');

        return redirect()->back();
    }
}
