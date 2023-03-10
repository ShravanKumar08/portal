<?php

namespace App\Http\Middleware;

use App\Models\Report;
use Closure;
use Illuminate\Http\Request;
use function abort;
use function auth;

class CheckEditReport
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $Model = $request->route()->parameters['report'];

        if(!is_a($Model, Report::class)){
            $Model = Report::find($Model);
        }

        abort_if(!$Model, 404, 'Not found');

        $request->request->add(compact('Model'));

        abort_if((auth()->user()->hasRole('admin') == false && $Model->status != 'A'), 403, 'Access Denied');

        return $next($request);
    }
}
