<?php

namespace App\Http\Controllers\Trainee;

use App\DataTables\LeaveDataTable;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckEditLeave;
use App\Models\Compensation;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function dd;
use function flash;
use function redirect;
use function view;

class LeaveController extends Controller {

    public function __construct()
    {
        $this->middleware(CheckEditLeave::class)->only(['edit', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LeaveDataTable $dataTable, Request $request , Leave $Leave) {
        $dataTable->role = "trainee";
        $data['statuses'] = $Leave::$status;
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('trainee.leaves.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Leave $leave) {
        $data['Model'] = $leave;        
        $this->_append_form_variables($data);
        return view('trainee.leaves.create',$data);
    }
    
    protected function _append_form_variables(&$data) {
        $data['status'] = Leave::$status;
        $data['employees'] = Employee::pluck("name", "id")->toArray();
        $data['Holidays'] = Holiday::pluck("date")->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Leave $Leave) {
        $this->_append_hidden_values($request);
        $this->validate($request, $Leave::getRules($request));

        $Leave->saveForm($request);
        $Leave->at_training_period = ($Leave->employee->employeetype == 'T') ? 1 : 0;
        $Leave->save();

        flash('Leave created successfully')->success();
        return redirect()->route('trainee.leave.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Leave $Leave) {
        $data['Model'] = $Leave;
        $data['Employee'] = Employee::where('id',$data['Model']->employee_id)->first();
        $data['Leaveitems'] = $data['Model']->leaveitems()->get();
        return view('trainee.leaves.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Leave $Leave) {
        $data['Model'] = $request->Model;
        $this->_append_form_variables($data);
        return view('trainee.leaves.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Leave $Leave) {
        $this->_append_hidden_values($request);
        $this->validate($request, $Leave::getRules($request, $Leave->id));

        $Leave = $request->Model;
        $Leave->saveForm($request);

        flash('Leave Updated successfully')->success();
        return redirect()->route('trainee.leave.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
//        Leave::find($id)->delete();
    }

    private function _append_hidden_values($request)
    {
        $request->request->add(['employee_id' => \Auth::user()->employee->id, 'status' => "P" ,'at_training_period' => 1]);
    }
    
}
