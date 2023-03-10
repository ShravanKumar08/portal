<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LateEntryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\LateEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;
use App\Models\Employee;
use App\Models\Report;
use App\Models\Officetiming;
use Carbon\Carbon;

class LateEntryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LateEntryDataTable $dataTable, Request $request, Employee $employee, LateEntry $LateEntry) {
        $query = Employee::oldest('name')->permanent();
        if($request->inactive_employee == 0){
            $query->active();
        }
        $data['all_employees'] = $query->get();
        $data['employees_list'] = $data['all_employees']->pluck("name", "id")->toArray();
        $data['request'] = $request;
        $data['statuses'] = $LateEntry::$status;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.late_entries.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request , LateEntry $LateEntry) {
        $LateEntry->employee_id = $request->employee_id;
        $data['Model'] = $LateEntry;
        $this->_append_form_variables($data);
        return view('admin.late_entries.create', $data);
    }

    protected function _append_form_variables(&$data) {
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, LateEntry $LateEntry) {
        $this->_validate($request);

        $this->_save($request, $LateEntry);
        $redirect = route('late_entries.index', ['employee_type' => $LateEntry->employee->employeetype]);
        flash('Late Entry created successfully')->success();
        return redirect()->to($redirect);
    }

    private function _validate($request, $id = null) {
        $this->validate($request, [
            'employee_id' => 'required',
            'date' => 'required',
        ]);
    }

    private function _save($request, $LateEntry) {
        $data = $request->except(['_token']);
        $LateEntry->fill($data);
        $date = Carbon::parse($request->date)->format("h:i:s");
        $start = Employee::find($request->employee_id)->officetiming->getSlotByDay(intval(Carbon::parse($request->date)->format("d")))->value['start'];
        $LateEntry->elapsed = gmdate('H:i:s', Carbon::parse($date)->diffInSeconds($start));
        $LateEntry->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(LateEntry $LateEntry, Employee $employee) {
        $data['Model'] = $LateEntry;
        $data['Employee'] = $employee->where('id', $data['Model']->employee_id)->first();
        return view('admin.late_entries.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(LateEntry $LateEntry, Employee $employee) {
        $data['Model'] = $LateEntry;
        $data['Employee'] = $employee->where('id', $data['Model']->employee_id)->first();
        $this->_append_form_variables($data);
        return view('admin.late_entries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, LateEntry $LateEntry) {
        $this->_validate($request);

        $this->_save($request, $LateEntry);
        $redirect = route('late_entries.index', ['employee_type' => $LateEntry->employee->employeetype]);
        flash('Late Entry Updated successfully')->success();
        return redirect()->to($redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(LateEntry $LateEntry) {
        $LateEntry->delete();
    }
    
    public function addremarks(Request $request) {
        $model = LateEntry::find($request->id);
        $model->remarks = $request->remarks;
        $model->status = $request->status;
        $model->save();
        return response()->json(['success' => 'Success!', 'status' => $model->status], 200);
    }

    public function getEntryDetail(Request $request) 
    {   
        $lateentry = LateEntry::find($request->id); 

        $report = Report::where("employee_id", $lateentry->employee_id)->where('date', Carbon::parse($lateentry->date)->toDateString())->first();

       // dd($report);
        $officetiming = $report->employee->officetiming;

        $extend_hours = $officetiming->totalOfficeHours;
        $lt_start_hour = $officetiming->value->lt_start;

        $start = substr($report->start,0,5);
       
        $time1 = $extend_hours;
        $time2 = $start;
        $time = strtotime($time1) + strtotime($time2) - strtotime('00:00');
        $endtime = date('g:i A', $time);

        $time1 = $start;
        $time2 = $lt_start_hour;
        $time = strtotime($time1) - strtotime($time2) + strtotime('00:00');
       $extended = date('H:i', $time);

        $times['endtime'] = $endtime;
        $times['extended'] = $extended;

    
    //    $time['endtime'] = $extend_hours + $start; //8:40
   //     $time['extended'] = $start - $lt_start_hour; //00:09 mins

        return response()->json($times);
    }


}
