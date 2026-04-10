<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tagihan;
class AutoDeleteTagihan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(cache()->has('auto_delete_tagihan')){
            return $next($request);
        }
        cache()->put('auto_delete_tagihan', true, now()->addHours(12));
        Tagihan::whereNotNull('tanggal_kadaluarsa')
        ->where('tanggal_kadaluarsa', '<', date('Y-m-d'))
        ->where(function($query){
            $query->where('bayar','')
            ->orWhere('bayar',0)
            ->orWhere('bayar',null);
        })
        ->delete();
        
        return $next($request);
    }
}
