<?php

namespace App\Http\Middleware;

use App\Models\Leave;
use Closure;
use Illuminate\Http\Request;

class CheckEditLeave {

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $Model = $request->route()->parameters['leave'];

        if(!is_a($Model, Leave::class)){
            $Model = Leave::find($Model);
        }

        abort_if(!$Model, 404, 'Not found');

        if (auth()->user()->hasRole('admin') == false) {
            abort_if(($Model->status != 'P' || $Model->employee_id != auth()->user()->employee->id), 403, 'Access Denied');
        }

        $request->request->add(compact('Model'));

        return $next($request);
    }

}
