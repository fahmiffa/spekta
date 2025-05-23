<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedIps = [
            // '103.47.132.48',      
            $request->server('SERVER_ADDR'), 
        ];

        $clientIp = $request->ip();

        if (!in_array($clientIp, $allowedIps)) {
            return abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
