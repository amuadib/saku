<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CetakStrukController extends Controller
{
    public function cetak(string $view, string $id)
    {
        return view('cetak.' . $view, [
            'data' => Cache::get($id, [])
        ]);
    }
}
