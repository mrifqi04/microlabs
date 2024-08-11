<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_media',
        'media_name',
        'leadtime',
        'barcode',
        'deleted_at',
        'status'
    ];

    function generateBarcode()
    {
        $rand = Str::uuid()->toString();
        $this->barcode = $rand;
        $this->save();
    }

    function PIC()
    {
        return $this->hasOne(User::class, 'id', 'pic');
    }

    function Analytics()
    {
        return $this->hasMany(Analytic::class, 'sample_id', 'id')
            ->where('type', 'media')
            ->orderBy('id', 'DESC');
    }

    function HistoryAnalytics($replication)
    {
        return $this->hasMany(Analytic::class, 'sample_id', 'id')
            ->where('type', 'media')
            ->where('replication', $replication)
            ->orderBy('id', 'DESC')
            ->get();
    }
}
