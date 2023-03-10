<?php

namespace App\Http\Middleware;

use App\Models\Userpermission;
use Closure;
use Illuminate\Http\Request;
use function abort;
use Auth;

class CheckEditPermission {

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $Model = $request->route()->parameters['userpermission'];

        if(!is_a($Model, Userpermission::class)){
            $Model = Userpermission::find($Model);
        }

        abort_if(!$Model, 404, 'Not found');
        abort_if((auth()->user()->hasRole('admin') == false && $Model->status != 'P'), 403, 'Access Denied');

        $request->request->add(compact('Model'));

        return $next($request);
    }

}
