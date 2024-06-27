<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'no_sample',
        'no_batch',
        'deskripsi_sample',
        'tanggal_terima',
        'parameter_testing_id',
        'tenggat_testing',
        'jumlah_sampel',
        'pic',
        'status',
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

        $outputBarcode = '/public/img/qr-code/sample/' . $rand . '.png';

        Storage::disk('local')->put($outputBarcode, $barcodeSample);

        $this->qr_code = $rand;
        $this->save();
    }

    function ParameterTesting()
    {
        return $this->hasOne(ParameterTesting::class, 'id', 'parameter_testing_id');
    }

    function Analytics()
    {
        return $this->hasMany(Analytic::class, 'sample_id', 'id')->orderBy('id', 'DESC');
    }
}
