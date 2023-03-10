<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\LateEntryDataTable;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\LateEntry;
use App\Models\Leave;
use App\Models\Report;
use App\Models\Userpermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LateEntryController extends Controller
{
    protected $employee;
    protected $now;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LateEntryDataTable $dataTable, Request $request, LateEntry $LateEntry)
    {
        $dataTable->role = "user";
        $data['statuses'] = $LateEntry::$status;
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('employee.late_entries.index',$data);
    }

     public function show(LateEntry $LateEntry)
    {
        $Model = $LateEntry;
        return view('employee.late_entries.view', compact('Model'));
    }
  
}
