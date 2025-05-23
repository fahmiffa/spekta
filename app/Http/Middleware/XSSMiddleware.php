<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSSMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        foreach ($input as $key => $value) {
            if (is_string($value) && stripos($value, '<script>') !== false) {
          

                toastr()->error('input tidak valid', ['timeOut' => 5000]);
                return redirect()->back()
                    ->withInput();
            }
        }

        return $next($request);
    }
}
