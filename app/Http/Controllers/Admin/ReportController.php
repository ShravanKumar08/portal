<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReportDataTable;
use App\Helpers\EmployeeHelper;
use App\Helpers\EntryHelper;
use App\Http\Controllers\Controller;
use App\Mail\ReleaselockNotification;
use App\Mail\ReportsStatusNotification;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Entry;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Technology;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Validator;
use function flash;
use function redirect;
use function response;
use function view;
use DB;

class ReportController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(ReportDataTable $dataTable, Request $request, Employee $employee, Report $report)
    {   
            $query = Employee::oldest('name')->permanent();
            if($request->inactive_employee == 0){
                $query->active();
            }               
            $data['all_employees'] = $query->get();
            $data['employees_list'] = $data['all_employees']->pluck("name", "id")->toArray();
            $data['statuses'] = $report::$status;
            $data['request'] = $request;
            return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.reports.index', $data);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Entry $entry)
    {
        $entry->employee_id = $request->employee_id;
        $data['Model'] = $entry;
        $this->_append_form_variables($data);
        return view('admin.reports.create', $data);
    }

    protected function _append_form_variables(&$data)
    {
        $data['status'] = Report::$status;
        $data['employees'] = Employee::query()->active()->oldest('name')->pluck("name", "id")->toArray();
        $technologies = Technology::query()->oldest('name')->active()->get();
        $data['technology_dropdown'] = $technologies->pluck('name', 'id')->toArray();
        $data['exclude_technology'] = $technologies->filter(function ($technology) {
            return $technology->exclude == 1;
        })->pluck('name')->toArray();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, Report $report)
    {
        $this->_validate($request);

        $this->_save($request, $report);
        $redirect = route('report.index', ['employee_type' => $report->employee->employeetype]);
        flash('Report created successfully')->success();
        return redirect()->to($redirect);
    }

    private function _save($request, $report)
    {
        $data = $request->except(['_token']);
        $report->fill($data);
        $report->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Request $request, Report $report)
    {
        $data['Model'] = $report;
        $data['Reportitems'] = $data['Model']->my_report_items();

        if ($request->ajax()) {
            return view('admin.reports.partials.show', $data);
        } else {
            return view('admin.reports.view', $data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Report $report, Request $request)
    {
        $data['Model'] = $data['Report'] = $report;
        $data['Reportitems'] = $data['Model']->my_report_items();

        $data['formUrl'] = route('employee.report.store');
        $data['formMethod'] = 'POST';

        $data['Report'] = $report;
        $technologies = Technology::query()->oldest('name')->active()->get();
        $data['status'] = ReportItem::$status;

        $data['employees'] = Employee::query()->active()->oldest('name')->pluck("name", "id")->toArray();
        $data['technology_dropdown'] = $technologies->pluck('name', 'id')->toArray();
        $data['exclude_technology'] = $technologies->filter(function ($technology) {
            return $technology->exclude == 1;
        })->pluck('name')->toArray();

        return view('admin.reports.edit', $data);
    }

    public function storeReportitem(Request $request)
    {
        $model = new ReportItem();
        $this->_validate_reportitem($request);

        $this->_save_reportitem($request, $model);
        return response()->json(['success' => 'Success!'], 200);
    }

    public function updateReportitem(Request $request, $id)
    {
        $model = ReportItem::find($request->id);
        $this->_validate_reportitem($request);
        $this->_save_reportitem($request, $model);
        return response()->json(['success' => 'Success!', 'id' => $request->id], 200);
    }

    private function _validate_reportitem($request)
    {
        $rules = [
            'start' => ['required', 'date_format:H:i', new \App\Rules\ReportItemValidator()],
            'end' => 'required',
            'technology_id' => 'required',
        ];

        $tech = Technology::find($request->technology_id);

        if ($tech && $tech->exclude == 0) {
            $rules = array_merge($rules, [
                'works' => 'required',
                'status' => 'required',
            ]);
        }

        $this->validate($request, $rules, [
            'technology_id.required' => 'The category field is required',
        ]);
    }

    private function _save_reportitem($request, $model)
    {
        $data = $request->except(['_token', 'projectname']);
        if ($name = $request->projectname) {
            $project = Project::firstOrCreate(['name' => $name]);
            $data['project_id'] = $project->id;
        }
        $model->fill($data);
        $model->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, Report $report)
    {
        $this->_validate($request);

        $this->_save($request, $report);
        $redirect = route('report.index', ['employee_type' => $report->employee->employeetype]);
        flash('Report Updated successfully')->success();
        return redirect()->to($redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Report $report)
    {
        $report->delete();
    }

    private function _validate($request, $id = null)
    {
        $this->validate($request, [
            'date' => 'required',
            'start' => 'required',
        ]);

    }

    private function _remarks_validate($request, $id = null)
    {
        $this->validate($request, [
            'end' => 'required',
        ]);
    }

    public function addremarks(Request $request)
    {
        $report = Report::find($request->id);

        if ($report->date != Carbon::now()->toDateString() && $request->status != 'D' && $request->status != 'S') {
            $this->_remarks_validate($request);
            $report->end = $request->end;
        }

        $report->remarks = $request->remarks;
        $report->status = $request->status;
        $report->save();
        
        $report->remarksMail();
        Mail::to($report->employee->email)->queue(new ReportsStatusNotification($report, true));

        if ($request->release_request) {
            $this->storeReleaselock($request);
        }

        $carbonDate = Carbon::parse($report->start);
        $entryHelper = new EntryHelper($report->employee, $carbonDate);
        $result = $entryHelper->start($request, $report->date);

        if($request->status == 'A'){
            $timing = $report->employee->officetiming->getValueByDate($carbonDate->day);
            if ($report->start > $timing->lt_half_day_excuse) {
                $entryHelper->datetime = Carbon::parse($report->date);
                $entryHelper->addHalfDay();
            } else if ($report->start > $timing->lt_perm) {
                $entryHelper->datetime = Carbon::parse($report->date);
                $entryHelper->addPermission();
            } else if ($report->start > $timing->lt_start) {
                $entryHelper->datetime = Carbon::parse($report->date.$report->start);
                $entryHelper->addLateEntry($timing);
            }
        }

        if ($result['status'] == 'success') {
            $entry = $result['entry'];
            $entry->mode = 'M';
            $entry->save();

            return response()->json(['success' => 'Success!', 'status' => $report->status]);
        } else {
            return response()->json(['message' => $result['message']], 422);
        }
    }

    public function getDailyMonthlyReport(Request $request)
    {
        $data['request'] = $request;
        $data['employees_list'] = Employee::oldest('name')->permanent()->active()->pluck("name", "id")->toArray();

        $query = Employee::query()->permanent()->oldest('name')->active();

        if ($employeeid = $request->employee_id) {
            $query->whereIn('id', $employeeid);
        }

        $data['employees'] = $query->paginate(20);

        return view('admin.reports.monthlyreports.daily', $data);
    }

    public function getmonthlyreportitems(Request $request)
    {
        $employee = Employee::find($request->employee_id);
        $EmployeeHelper = new EmployeeHelper($employee);
        $data = $EmployeeHelper->getMonthlyReportitemsData($request);
        return view('layouts.partials.monthlyreports.dailyitems', $data, compact('employee'));
    }

    public function getLeaveMonthlyReport(Request $request)
    {
        $data['request'] = $request;
        $data['employees_list'] = Employee::oldest('name')->permanent()->active()->pluck("name", "id")->toArray();
        $query = Employee::query()->permanent()->oldest('name')->active();

        if ($employeeid = $request->employee_id) {
            $query->whereIn('id', $employeeid);
        }
        $data['employees'] = $query->get();

        $month_year = Carbon::parse($request->month_year);
        $data['year'] = $month_year->year;
        $data['month'] = $month_year->month;
        $data['yearly'] = '';

        return view('admin.reports.monthlyreports.leave', $data);
    }

    public function getleaveitems(Request $request)
    {
        $employee = Employee::find($request->employee_id);
        $EmployeeHelper = new EmployeeHelper($employee);
        $data = $EmployeeHelper->getMonthlyLeaveReportitemsData($request);

        return view('layouts.partials.monthlyreports.leaveitems', $data);
    }

    public function getLeaveYearlyReport(Request $request)
    {
        $data['request'] = $request;
        $data['employees_list'] = Employee::oldest('name')->permanent()->active()->pluck("name", "id")->toArray();
        $query = Employee::query()->permanent()->oldest('name')->active();

        if ($employeeid = $request->employee_id) {
            $query->whereIn('id', $employeeid);
        }
        $data['employees'] = $query->paginate(20);

        $data['year'] = $request->month_year;
        $data['month'] = '';
        $data['yearly'] = 'yearlyLeaves';

        return view('admin.reports.monthlyreports.leave', $data);
    }

    public function getyearlyleaveitems(Request $request)
    {
        $employee = Employee::find($request->employee_id);
        $EmployeeHelper = new EmployeeHelper($employee);
        $data = $EmployeeHelper->getMonthlyLeaveReportitemsData($request);

        return view('layouts.partials.monthlyreports.leaveitems', $data);
    }

    public function releaseRequest(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->storeReleaselock($request);
        } else {
            $reportitems = ReportItem::where('report_id', $request->report_id)->where('release_request', 1)->get();
            if ($request->haslockValue == 1) {
                return view('admin.reports.partials.releaselocktable', compact('reportitems'));
            } else {
                return view('admin.reports.partials.release_request', compact('reportitems'));
            }
        }
    }

    public function storeReleaselock(Request $request)
    {
        foreach ($request->release_request as $key => $release_request) {
            $reportitem = ReportItem::find($key);
            $reportitem->release_request = $release_request;
            $reportitem->lock = ($release_request == 0) ? 0 : 1;
            $reportitem->save();
        }
        $report = $reportitem->report()->first();
        Mail::to($report->employee->email)->queue(new ReleaselockNotification($report));
    }

    public function getReportitemsedit(Request $request)
    {
        if ($request->reportitem_id != null) {
            $data['Reportitem'] = ReportItem::where('id', $request->reportitem_id)->first();
        } else {
            $data['Reportitem'] = new ReportItem();
            $data['Reportitem']->report_id = $request->report_id;
        }

        $Reportitem = $data['Reportitem'];
        $data['Report'] = $data['Reportitem']->report;
        $technologies = Technology::query()->oldest('name')->active()->get();
        $data['status'] = ReportItem::$status;

        $data['employees'] = Employee::query()->active()->oldest('name')->pluck("name", "id")->toArray();
        $data['technology_dropdown'] = $technologies->pluck('name', 'id')->toArray();
        $data['exclude_technology'] = $technologies->filter(function ($technology) {
            return $technology->exclude == 1;
        })->pluck('name')->toArray();

        if ($Reportitem->id) {
            $data['route'] = 'report.updateReportitem';
        } else {
            $data['route'] = 'report.storeReportitem';
        }

        return view('admin.reports.partials.reportitem_form', $data);
    }

    public function searchProject(Request $request)
    {
        $query = @$request->term['term'] ?: '';
        $projects = Project::query()->whereNotNull('name')->where('name', 'LIKE', '%' . $query . '%')->limit(10)->active()->oldest('name')->get();

        $data = array();
        foreach ($projects as $project) {
            $data[] = array('text' => $project->name, 'id' => $project->name);
        }

        return response()->json(['results' => $data]);
    }

    public function setPermissionTime(Request $request)
    {
        if ($request->permission == Technology::PERMISSION_UUID) {
            $report = Report::find($request->report_id);
            $userpermission = Userpermission::where('employee_id', $report->employee_id)->where('date', $report->date)->first();
            if (!empty($userpermission)) {
                $start = Carbon::parse($userpermission->start)->format('H:i');
                $end = Carbon::parse($userpermission->end)->format('H:i');
                return response()->json(['success' => 'Success', 'start' => $start, 'end' => $end], 200);
            }
        }
    }

    public function searchTechnolgy(Request $request)
    {
        $technolgy_id = null;

        $project = Project::where('name', $request->value)->first();

        if ($project) {
            $reportitem = ReportItem::query()
                ->selectRaw('technology_id')
                ->where('project_id', $project->id)
                ->whereHas('report', function ($q) {
                    $q->where('employee_id', \Auth::user()->employee->id);
                })
                ->exclude()
                ->groupBy('technology_id')
                ->orderByRaw('count(*) DESC')
                ->first();

            $technolgy_id = @$reportitem->technology_id;
        }

        return response()->json(['id' => $technolgy_id]);
    }

    public function deleteReportitems(Request $request, ReportItem $reportitem)
    {
        $Reportitem = $reportitem::find($request->id);
//        $this->_validate_lock($Reportitem);
        $Reportitem->delete();
        return response()->json(true, 200);
    }

    protected function _validate_lock($Reportitem)
    {
        abort_if($Reportitem->lock == 1, 403, 'Access Denied');
    }

    public function getReportitems(Request $request)
    {
        $this->_append_form_variables($data);
        $data['Report'] = Report::find($request->id);
        $data['Reportitems'] = @$data['Report']->reportitems()->oldest('start')->get();
        $data['action'] = true;
        $data['condition'] = 'admin-report-edit';
        return view("layouts.partials.reportitemstable", $data);
    }

    public function getMonthlyAssessmentReport(Request $request)
    {
        $data['request'] = $request;
        
        $query = Employee::oldest('name')->permanent();

        if($request->inactive_employee == 0){
            $query->active();
        }

        if($emp = $request->employee_id)
        {
            $query->whereIn('id', $emp);   
        }

        if($exc_emp = $request->exclude_employee_id)
        {
            $query->whereNotIn('id', $exc_emp);   
        }

        $data['employees'] = $query->get(); 

        $query = Employee::oldest('name')->permanent();
        if($request->inactive_employee == 0){
            $query->active();
        }   
        $data['all_employees'] = $query->get();
        $data['employees_list'] = $data['all_employees']->pluck("name", "id")->toArray();

        if($request->has('month_year')){
            $dateObj = \Carbon\Carbon::createFromFormat('Y-m-d', $request->month_year.'-01');
        }else{
            $dateObj = \Carbon\Carbon::now();
        }

        $data['start'] = $dateObj->startOfMonth()->toDateString();
        $data['end'] = $dateObj->endOfMonth()->toDateString();

        return view('admin.reports.assessment', $data);
    }
    
    public function getMonthlyWorkingHoursReport(Request $request)
    {
        if($emp = $request->employee_id){
            $emp = implode("','", $emp);
        }
       
        $data['request'] = $request;
        $data['employees_list'] = Employee::oldest('name')->permanent()->active()->pluck("name", "id")->toArray();
        $data['project'] = Project::getProjectReportDataByEmployees($emp, $request);

        return view('admin.reports.project',$data);
    }

    public function showMonthlyWorkingHoursReport(Request $request)
    {
        return view('layouts.partials.projectitemstable', compact('request'));
    }

    public function getEndTime(Request $request)
    {
        $time = null;

        $report = Report::find($request->report_id);
        $entry = Entry::where('employee_id',$report->employee_id)->where('date',$report->date)->first();
        $entrytime = $entry->entryitems()->where('inout','O')->latest('datetime')->first();

        if($datetime = @$entrytime->datetime){
            $time = Carbon::parse($datetime)->format('H:i');
        }

        return $time;
    }

    public function bulkchangestatus(Request $request) {
        $ids = explode(',', $request->id);

        foreach($ids as $report_id){
            $model = Report::find($report_id);
            $model->remarks = $request->remarks;
            $model->status = $request->status;
            $model->save();
            if($model->status != 'R'){
                Mail::to($model->employee->email)->queue(new ReportsStatusNotification($model, true));
            }   
       }

       return response()->json(true);
   }
}
