<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CompensationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Compensation;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leavetype;
use App\Models\LeaveItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;
use DB;

class CompensationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(CompensationDataTable $dataTable, Request $request, Employee $employee, Compensation $Compensation) {
        if($emp = $request->employee_id){
            $emp = implode("','", $emp);
        }
      
        $data['request'] = $request;

        $query = Employee::oldest('name')->permanent();
        
        if($request->inactive_employee == 0){
            $query->active();
        }   
        
        $data['all_employees'] = $query->get();
        $data['employees_list'] = $data['all_employees']->pluck("name", "id")->toArray();
        $data['statuses'] = $Compensation::$status;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.compensations.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Compensation $compensation) {
        $compensation->employee_id = $request->employee_id;
        $data['Model'] = $compensation;
        $this->_append_form_variables($data);
        return view('admin.compensations.create',$data);
    }
    
    protected function _append_form_variables(&$data) {
        $data['types'] = Compensation::$types;
        $data['days'] = Compensation::$days;
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['Holidays'] = Holiday::pluck("date")->toArray();
    }

    public function getCompensationform(Request $request , Compensation $compensation) {
        $compensation->employee_id = $request->employee_id;
        $data['Model'] = $compensation;
        $this->_append_form_variables($data);
        $data['includeScripts'] = true;
        return view('admin.compensations.partials.form', $data);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Compensation $compensation) {
        $this->validate($request, $compensation::getRules($request));
        
        $compensation->saveForm($request);

        flash('Compensation created successfully')->success();
        
        $redirect = route('compensation.index', ['employee_type' => $compensation->employee->employeetype]);
        if($request->ajax()){
            return response()->json(['redirect' => $redirect]);
        }else{
            return redirect()->to($redirect);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Compensation $compensation, Employee $employee) {
        $data['Model'] = $compensation;
        $data['Employee'] = $employee->where('id', $data['Model']->employee_id)->first();
        return view('admin.compensations.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id) {
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Compensation $compensation) {
        $compensation->delete();
    }

    public function addremarks(Request $request) {
        $compensation = Compensation::find($request->id);
        $current_year= Carbon::now()->year;
       
            foreach ($compensation->leaveitems as $compensate_leave) {
                $e_remaining_casual_count = $compensation->employee->getRemainingCasualCount($current_year);
                if($e_remaining_casual_count >= $compensate_leave->days){
                    $compensate_leave->update(['leavetype_id' =>  Leavetype::getCasual()->id ]);
                }else{
                    $compensate_leave->update(['leavetype_id' => Leavetype::getpaid()->id ]);
                }
                DB::table('compensates')->where('compensation_id' , $compensation->id)->delete();
            }
        $compensation->is_paid = $request->is_paid;
        $compensation->remarks = $request->remarks;
        $compensation->save();
    }
}
