<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterTesting extends Model
{
    use HasFactory;

    protected $fillable = [
        'parameter_uji',
        'leadtime',
        'deleted_at'
    ];
}
