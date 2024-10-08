<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeTesting extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'type_code'
    ];

    public function Samples()
    {
        return $this->hasMany(Sample::class, 'type_id', 'id');
    }
}
