<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class IsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $per): Response
    {
        if (Auth::check() && Auth::user()->ijin($per)) {
            return $next($request);
        }

        toastr()->error('Unauthorized Access', ['timeOut' => 5000]);
        return redirect()->route('main');
    }
}
