<?php

namespace App\Http\Controllers;

use App\Models\DataStruk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CetakStrukController extends Controller
{
    public function cetak(Request $request, string $view, string $id, string $mode = 'cache')
    {
        $data = [];
        if ($mode == 'cache') {
            $data = Cache::get($id, []);
        } else if ($mode == 'db') {
            $data = DataStruk::where('kode', $id)->first()->data ?? [];
        } else if ($mode == 'raw') {
            $data = json_decode(base64_decode($request->get('data')), true) ?? [];
        }

        return view('cetak.template', [
            'view' => $view,
            'data' => $data
        ]);
    }
}
