<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserPermissionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckCreatePermission;
use App\Http\Middleware\CheckEditPermission;
use App\Mail\PermissionNotification;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LateEntry;
use App\Models\Userpermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use function flash;
use function redirect;
use function response;
use function view;
use App\Models\Report;
use App\Models\Technology;
use Illuminate\Support\Facades\Validator;

class UserpermissionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware(CheckCreatePermission::class)->only(['create', 'store']);
        $this->middleware(CheckEditPermission::class)->only(['edit', 'update']);
    }
    
    public function index(UserPermissionDataTable $dataTable, Request $request, Employee $employee, Userpermission $Userpermission)
     {
        $data['request'] = $request;
        $data['employees_list'] = $employee->oldest('name')->active()->where('employeetype', $request->employeetype)->pluck("name", "id")->toArray();
        $data['statuses'] = $Userpermission::$status;

        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.userpermissions.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request , Userpermission $userPermission) {
        $userPermission->employee_id = $request->employee_id;
        $data['Model'] = $userPermission;
        $this->_append_form_variables($data);
        return view('admin.userpermissions.create', $data);
    }

    protected function _append_form_variables(&$data) {
        $data['status'] = Userpermission::$status;
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['Holidays'] = Holiday::pluck("date")->toArray();
    }
    
    public function getPermissionform(Request $request , Userpermission $userPermission) {
        $userPermission->employee_id = $request->employee_id;
        $data['Model'] = $userPermission;
        $data['employee'] = $userPermission->employee;
        $this->_append_form_variables($data);
        $data['includeScripts'] = true;
        return view('admin.userpermissions.partials.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Userpermission $Userpermission) {
        $this->validate($request, $Userpermission::getRules($request));
        $Userpermission->saveForm($request);

        $employeeType = $Userpermission->employee->employeetype;

        $Userpermission->at_training_period = ($employeeType == 'T') ? 1 : 0;
        $Userpermission->save();

        if($request->ajax()){
            return response()->json(compact('employeeType'));
        }else{
            flash('Userpermission created successfully')->success();
            return redirect()->route('userpermission.index', ['employee_type' => $employeeType]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Userpermission $Userpermission, Employee $employee) {
        $data['Model'] = $Userpermission;
        $data['Employee'] = $employee->where('id', $data['Model']->employee_id)->first();
        return view('admin.userpermissions.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Userpermission $Userpermission, Employee $employee) {
        $data['Model'] = $Userpermission;
        $data['Employee'] = $employee->where('id', $data['Model']->employee_id)->first();
        $this->_append_form_variables($data);
        return view('admin.userpermissions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Userpermission $Userpermission) {
       
        $this->validate($request, $Userpermission::getRules($request, $Userpermission->id));

        $Userpermission->saveForm($request);

        flash('Userpermission Updated successfully')->success();
       // return redirect()->route('userpermission.index');
       $redirect = route('userpermission.index', ['employee_type' => $Userpermission->employee->employeetype]);
        if($request->ajax()){
            return response()->json(['redirect' => $redirect]);
        }else{
            return redirect()->to($redirect);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Userpermission $Userpermission) {
        $Userpermission->delete();
    }

    public function addremarks(Request $request) {
        
        $Userpermission = Userpermission::find($request->id);
        $this->saveRemarksStatus($Userpermission, $request);

        return response()->json(['success' => 'Success!', 'status' => $Userpermission->status], 200);
    }
    
    public function showAudits(Request $request) {
        $UserPermission = Userpermission::find($request->userpermission_id);
        $datas = $UserPermission->audits()->latest()->get();
        return view("layouts.partials.audits", compact('datas'));
    }
    
    public function bulkchangestatus(Request $request) {
        $ids = explode(',', $request->id);
        foreach($ids as $perm_id){
            $Userpermission = Userpermission::find($perm_id);
            $this->saveRemarksStatus($Userpermission, $request);
        }
        return redirect()->back();
    }
    
    public function saveRemarksStatus($Userpermission, $request)
    {
        $rules = [];

        if($request->status == 'U'){
            $rules['date'] = 'required';
            $rules['time'] = 'required';
        }

        $this->validate($request,$rules, [
            'status' => [function ($attribute, $value, $fail) use ($Userpermission) {
                $permission_data = $Userpermission->where('employee_id' , $Userpermission->employee_id)->where('date', $Userpermission->date)->where('status', 'A')->first();
                if ($value==='A' && $permission_data) {
                        return $fail('The permission is already given for this date.');
                    }
                },
            ],
        ]);

       if($request->status == 'D' || $request->status == 'U'){
            $report = Report::where('employee_id' , $Userpermission->employee_id)->where('date', $Userpermission->date)->first();
            
            if($report){
                $report->reportitems()->where('technology_id', Technology::PERMISSION_UUID)->forceDelete();
            }
        }

        if($request->status == 'U'){
            $date = $request->date;
            $time = $request->time;
            Report::where('employee_id', $Userpermission->employee_id)->whereDate('date' , $date)->update(['start' => $time]);
        }

        $Userpermission->remarks = $request->remarks;
        $Userpermission->status = $request->status;
        $Userpermission->save();
        $Userpermission->processCompensate();
        LateEntry::where('employee_id', $Userpermission->employee_id)->whereDate('date', $Userpermission->date)->update(['status' => $Userpermission->status]);
        $Userpermission->remarksMail();
        Mail::to($Userpermission->employee->email)->queue(new PermissionNotification($Userpermission));
    }
}
