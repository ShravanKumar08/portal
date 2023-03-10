<?php

namespace App\Http\Controllers\Trainee;

use Illuminate\Http\Request;
use App\DataTables\LateEntryDataTable;
use App\Http\Controllers\Controller;
use App\Models\LateEntry;
use App\Models\Employee;

class LateEntryController extends Controller
{
    public function index(LateEntryDataTable $dataTable, Request $request, Employee $employee, LateEntry $LateEntry) {
        $query = Employee::oldest('name')->permanent();
        if($request->inactive_employee == 0){
            $query->active();
        }
        $data['all_employees'] = $query->get();
        $data['employees_list'] = $data['all_employees']->pluck("name", "id")->toArray();
        $data['request'] = $request;
        $data['statuses'] = $LateEntry::$status;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('trainee.late_entries.index',$data);
    }
}
