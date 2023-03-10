<?php

namespace App\Http\Middleware;

use App\Helpers\AppHelper;
use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckCreatePermission {

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $employee_id = auth()->user()->hasRole('admin') ? app('request')->input('employee_id') : Auth::user()->employee->id;
        
        if ($employee_id && AppHelper::allowToCreatePermission($employee_id) == false) {
            flash('You have exceed your max allowed permission limit', 'danger');
            return redirect()->back();
        }

        return $next($request);
    }

}
