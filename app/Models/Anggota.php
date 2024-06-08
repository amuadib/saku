<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasUuids;
    protected $table = 'anggota';
}
