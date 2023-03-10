<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;

class CheckCamsunit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $camsunit = Setting::fetch('CAMSUNIT_AUTH_TOKEN');

        if($request->auth_token == @$camsunit['token'] && @$camsunit['status']){
            return $next($request);
        }

        echo 'ok'; //Note: it should return a string only.
        exit;
    }
}
