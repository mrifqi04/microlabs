<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'instrument_id',
        'scan_in',
        'scan_out',
        'pic',
        'status'
    ];

    public function Sample() {
        return $this->hasOne(Sample::class, 'id', 'sample_id');
    }

    public function Instrument() {
        return $this->hasOne(Instrument::class, 'id', 'instrument_id');
    }

    public function PIC() {
        return $this->hasOne(User::class, 'id', 'pic');
    }
}
