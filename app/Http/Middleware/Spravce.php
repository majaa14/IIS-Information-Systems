<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Spravce
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->check() || (auth()->user()->role != 'správce' && auth()->user()->role != 'admin')){
            session()->flash('error', 'ERROR! K této stránce nemáte přístup!');
            return redirect()->route('is')->with(['success' => false]);
        }
        return $next($request);
    }
}
