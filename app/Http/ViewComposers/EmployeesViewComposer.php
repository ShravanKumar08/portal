<?php

namespace App\Http\ViewComposers;

use App\Models\Employee;
use Illuminate\Contracts\View\View;

class EmployeesViewComposer {

    public function compose(View $view) {
        $employee = \Auth::user()->employee ?? new Employee();
        $employee->appendCustomFields();

        $view->with('auth_employee', $employee);
    }
}
