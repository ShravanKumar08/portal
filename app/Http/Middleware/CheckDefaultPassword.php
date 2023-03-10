<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckDefaultPassword {

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if($user = auth()->user()){
            if(Hash::check(User::DEFAULT_PASSWORD, $user->password)){
                if(auth()->user()->hasRole('employee') && !in_array($request->route()->getName(), ['employee.changepassword', 'employee.profile'])){
                    flash('You are using default password. Update your password', 'danger');
                    return redirect()->to('employee/profile');
                }
            }
        }

        return $next($request);
    }

}
