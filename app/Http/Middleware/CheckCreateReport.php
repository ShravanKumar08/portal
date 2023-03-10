<?php

namespace App\Http\Middleware;

use App\Models\Report;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use function abort;
use function auth;
use auth;

class CheckCreateReport
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $employee_id = auth()->user()->hasRole('admin') ? app('request')->input('employee_id') : Auth::user()->employee->id;

        if($employee_id){
            $report = Report::where('employee_id', $employee_id)->where('date', Carbon::now()->toDatestring())->first();

            if(@$report->status == 'S'){
                flash('You have already sent your report for the day', 'danger');
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
