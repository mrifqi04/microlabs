<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_instrument',
        'nama_instrument',
        'tanggal_kalibrasi',
        'tanggal_rekalibrasi',
        'deleted_at'
    ];

    function generateBarcode()
    {
        $rand = Str::uuid()->toString();

        $barcodeSample = QrCode::format('png')
            ->size(500)
            ->color(0, 0, 0)
            ->eyeColor(0, 39, 178, 155, 0, 0, 0)
            ->eyeColor(1, 39, 178, 155, 0, 0, 0)
            ->eyeColor(2, 39, 178, 155, 0, 0, 0)
            ->errorCorrection('H')
            ->generate($rand);

        $outputBarcode = '/public/img/qr-code/instrument/' . $rand . '.png';

        Storage::disk('local')->put($outputBarcode, $barcodeSample);

        $this->qr_code = $rand;
        $this->save();
    }

}
