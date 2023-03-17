<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Sentry\State\Scope;
    
class SentryUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && app()->bound('sentry'))
        {
            \Sentry\configureScope(function (Scope $scope): void {
                $scope->setUser([
                    'id'    => Auth::user()->id,
                    'email' => Auth::user()->email,
                    'name'  => Auth::user()->name,
                ]);
            });
        }

        return $next($request);
    }
}