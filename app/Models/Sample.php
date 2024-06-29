<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
