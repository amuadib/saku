<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataStruk extends Model
{
    protected $table = 'data_struk';
    public $timestamps = false;
    protected $casts = [
        'data' => 'array',
    ];
}
