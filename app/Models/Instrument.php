<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        $this->qr_code = $rand;
        $this->save();
    }

}
