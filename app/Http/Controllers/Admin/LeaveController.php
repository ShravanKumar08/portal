<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LeaveDataTable;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Mail\LeaveNotification;
use App\Mail\PermissionNotification;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\LeaveItem;
use App\Models\Leavetype;
use App\Models\Officetiming;
use App\Models\Report;
use App\Models\Userpermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use function dd;
use function flash;
use function redirect;
use function response;
use function route;
use function view;

class LeaveController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LeaveDataTable $dataTable, Request $request, Employee $employee, Leave $leave)
    {
        $query = Employee::oldest('name');

        if($request->employeetype == "P") {
            $query->permanent();
        }else if($request->employeetype == "T") {
            $query->trainee();
        }

        if($request->inactive_employee == 0){
            $query->active();
        }             
        $data['all_employees'] = $query->get();
        $data['employees_list'] = $data['all_employees']->pluck("name", "id")->toArray();
        $data['statuses'] = $leave::$status;
        $data['request'] = $request;
        
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.leaves.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Leave $leave)
    {
        $leave->employee_id = $request->employee_id;
        $data['Model'] = $leave;
        $this->_append_form_variables($data);

        return view('admin.leaves.create', $data);
    }

    protected function _append_form_variables(&$data)
    {
        $data['status'] = Leave::$status;
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['Holidays'] = Holiday::pluck("date")->toArray();
    }

    /**
     * Getting Ajax Leave Form details
     */
    public function getLeaveform(Request $request, Leave $leave)
    {
        $leave->employee_id = $request->employee_id;
        $data['Model'] = $leave;
        $this->_append_form_variables($data);
        $data['includeScripts'] = true;
        return view('admin.leaves.partials.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, Leave $leave)
    {
        $this->validate($request, $leave::getRules($request));

        $leave->saveForm($request, $leave);
        $leave->at_training_period = ($leave->employee->employeetype == 'T') ? 1 : 0;
        $leave->save();

        flash('Leave created successfully')->success();
        $redirect = route('leave.index', ['employee_type' => $leave->employee->employeetype]);
        if($request->ajax()){
            return response()->json(['redirect' => $redirect]);
        }else{
            return redirect()->to($redirect);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Leave $leave, Employee $employee)
    {
        $data['Model'] = $leave;
        $data['Employee'] = $employee->where('id', $data['Model']->employee_id)->first();
        $data['Leaveitems'] = $data['Model']->leaveitems()->get();
        return view('admin.leaves.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['Model'] = $this->_get_model($id);
        $this->_append_form_variables($data);
        return view('admin.leaves.edit', $data);
    }

    private function _get_model($id)
    {
        return Leave::query()->withoutGlobalScope('permanent')->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $leave = $this->_get_model($id);
        $this->validate($request, $leave::getRules($request, $leave->id));

        $leave->saveForm($request, $leave);

        flash('Leave Updated successfully')->success();
        $redirect = route('leave.index', ['employee_type' => $leave->employee->employeetype]);
        if($request->ajax()){
            return response()->json(['redirect' =>  $redirect]);
        }else{
            return redirect()->$redirect;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Leave::query()->withoutGlobalScope('permanent')->where('id', $id)->delete();
    }

    public function addremarks(Request $request)
    {
        $model = $this->_get_model($request->id);
        $this->saveRemarksStatus($model, $request);
        return response()->json(['success' => 'Success!', 'status' => $model->status, 'modalid' => 'RemarksModal'], 200);
    }
    
    public function toggleLeave(Request $request) {
        if ($request->isMethod('post')) {
            $leaveitem = LeaveItem::find($request->leaveitem_id);
            $leaveitem->leavetype_id = $request->leavetype_id;
            $leaveitem->save();
        } else {
            $data['employee_name'] = $request->employee_name;
            $data['leaveitems'] = LeaveItem::where('leave_id',$request->leave_id)->get();
            $data['leavetypes'] = Leavetype::get()->pluck('name','id')->toArray();
            return view('admin.leaves.toggleLeave', $data);
        }        
    }
    
    public function convertLeave(Request $request) {
        if ($request->isMethod('post')) {
            $leave = Leave::find($request->leave_id);
            $leave->status = 'D';            
            $userPermission = new Userpermission();
            $this->validate($request, $userPermission::getRules($request));
            $leave->save();
            $leave->processLeaveRequest();
            $userPermission->saveForm($request);
            return response()->json(['success' => 'Success', 'modalid' => 'ConvertLeaveModal'], 200);
        } else {
            $data['Model'] = $data['leave'] = Leave::where('id', $request->leave_id)->first();
            $this->_append_form_variables($data);
            $data['employee'] = $data['Model']->employee;
            $report = $data['employee']->reports->where('date', $data['Model']->start)->first();
            $data['start'] = $data['employee']->officetiming->value->start;
            $data['end'] = AppHelper::formatTimestring(@$report->start, 'H:i');            
            $data['Status'] = 'A';
            return view('admin.leaves.convertLeave', $data);
        }        
    }
    
    public function showAudits(Request $request) {
        $leave = Leave::find($request->leave_id);
        $datas = $leave->audits()->latest()->get();
        return view("layouts.partials.audits", compact('datas'));
    }
    
    public function bulkchangestatus(Request $request) {
         $ids = explode(',', $request->id);
         foreach($ids as $leave_id){
            $model = Leave::find($leave_id);
            $this->saveRemarksStatus($model, $request);
            sleep(2);
        }
        return redirect()->back();
    }
    
     public function saveRemarksStatus($model, $request){
        $model->remarks = $request->remarks;
        $model->status = $request->status;
        $model->save();
        $model->processLeaveRequest();
        $model->remarksMail();
        Mail::to($model->employee->email)->queue(new LeaveNotification($model));        
    }
}
