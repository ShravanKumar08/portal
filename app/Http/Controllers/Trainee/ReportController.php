<?php

namespace App\Http\Controllers\Trainee;

use App\DataTables\ReportDataTable;
use App\Helpers\AppHelper;
use App\Helpers\EmployeeHelper;
use App\Helpers\EntryHelper;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckCreateReport;
use App\Http\Middleware\CheckEditReport;
use App\Mail\ReportsMail;
use App\Models\Compensation;
use App\Models\Employee;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Holiday;
use App\Models\Project;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Setting;
use App\Models\Technology;
use App\Models\UserSettings;
use App\Models\Userpermission;
use App\Rules\ReportValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Helpers\GithubHelper;
use App\Models\LateEntry;

use function abort;
use function dd;
use function flash;
use function redirect;
use function response;
use function route;
use function view;
Use DB;
use App\Models\Officetimingslot;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function __construct()
    {
        $this->middleware(CheckCreateReport::class)->only(['create', 'store']);
        $this->middleware(CheckEditReport::class)->only(['edit', 'update']);
    }

    public function index(ReportDataTable $dataTable, Request $request, Report $report)
    {
        $dataTable->role = "trainee";
        $data['statuses'] = $report::$status;
        $data['request'] = $request;
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('trainee.reports.index', $data);
    }

    public function create(Report $Report,Request $request )
    {
        $employee = \Auth::user()->employee;
        $data['Report'] = Report::getTodayReport($employee);
        $data['report_sort'] = @$employee->officetiming->currentdayslot->report_sort;


        $Report = Report::query()->where('employee_id', $employee->id)
                ->where('date', Carbon::yesterday()->toDateString())->get();
        $official_holidays = Holiday::where('date', Carbon::yesterday()->toDateString())->count();
        
        if($Report->isEmpty() &&  $official_holidays == '0' && Carbon::yesterday()->dayOfWeek != Carbon::SUNDAY){
            $data['report_date'] =[ Carbon::yesterday()->toDateString() => Carbon::yesterday()->toDateString(),
                                        Carbon::now()->toDateString() => Carbon::now()->toDateString() ];
        }   
        
    
        // $timestart = $officetimings->start;
        // $timeend = $officetimings->end;

        // if ($timestart > $timeend){ 
        //     $datas[] = Carbon::yesterday()->toDateString();
        //     $datas[] = Carbon::now()->toDateString();
        //     $datas[] = Carbon::tomorrow()->toDateString();
        //     $data['Report'] = $Report->whereIn('date',$datas)->where('status','R')->orderBy('date','ASC')->first();
        // } else{
        //     $data['Report'] = $Report->where(['date' => Carbon::now()->toDateString()])->first();
        // }

        $this->_append_form_variables($data);

        $data['formUrl'] = route('trainee.report.store');
        $data['formMethod'] = 'POST';

        if ($request->ajax()) {
            $validator = \Validator::make($request->all(), [
                'name' => 'unique:projects,name'

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
            return response()->json(['success' => 'Record is successfully added']);
        }
       
        return view('trainee.reports.create', $data);
    }
    
    protected function _append_form_variables(&$data)
    {
        $data['status'] = ReportItem::$status;
        $technologies = Technology::query()->oldest('name')->active()->get();
        $data['technology_dropdown'] = $technologies->pluck('name', 'id')->toArray();
        $data['exclude_technology'] = $technologies->filter(function ($technology) {
            return $technology->exclude == 1;
        })->pluck('name')->toArray();
    }

    public function store(Request $request)
    {
        $this->_save($request);
    }

    public function update(Request $request, $id)
    {
        $this->_save($request);
    }

    private function _validate_reportitem($request)
    {
            $rules = [
                'start' => ['required','date_format:H:i',new \App\Rules\ReportItemValidator()],
                'end' => 'required|different:start',
        //              'end' => 'required|date_format:H:i|after:start',
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
        //                'end.after' => 'The end must be a time after start',
                'technology_id.required' => 'The category field is required',
                'works.required' => 'The summary field is required',
            ]);
    }

    private function _save_reportitem($request, $model)
    {
        $data = $request->except(['_token', 'projectname']);
        if($name = $request->projectname){
            $project = Project::firstOrCreate(['name' => $name]);
            $data['project_id'] = $project->id;
        }
        $model->fill($data);
        $model->save();
    }

    public function getReportitems(Request $request)
    {
        $this->_append_form_variables($data);//call above function
        $data['Report'] = Report::find($request->id);
        $data['Reportitems'] = @$data['Report']->my_report_items();

        $extended_break = false;
        $actual_endtime = $data['Report']->actualEndTime;

        if($actual_endtime){
            $extended_break=$data['Report']->extendedBreak;
            $data['final_endtime'] = Carbon::parse($data['Report']->finalEndTime)->format('h:i A');

        } else {
            $data['final_endtime'] = null;
        }
        $data['extended_break'] = $extended_break;
  
        return view("trainee.reports.partials.reportitems", $data);
    }

    protected function _append_mismatched_times($report, &$Reportitem)
    {
        if(@$report->reportitems()->whereNotNull('project_id')->count() == 0){
            $Reportitem->start = $report->start;
            $first_break = $report->reportitems()->oldest('start')->where('technology_id', Technology::BREAK_UUID)->first();
            $Reportitem->end = $first_break ? $first_break->start : $report->start;
        }else{
            $Reportitem->start = $Reportitem->end = $Reportitem->report->lastreportitem()->end;
            $Reportitems = @$report->reportitems()->oldest('start')->get();

            for ($i = 1; $i < $Reportitems->count(); $i++) {
                if ($Reportitems[$i]->start != $Reportitems[$i - 1]->end) {
                    $Reportitem->start = $Reportitems[$i - 1]->end;
                    $Reportitem->end = $Reportitems[$i]->start;
                    break;
                }
            }
        }

//        if($Reportitem->start == $Reportitem->end && $report->date == Carbon::now()->toDateString()){
//            $Reportitem->end = $report->actualEndtime;
//        }
    }

    public function updateReportitems(Request $request)
    {
        $pk = $request->pk;
        $col = $request->name;
        $value = $request->value;

        if ($col == "project") {
            $project = Project::firstOrCreate(['name' => $value]);
            $col = 'project_id';
            $value = $project->id;
        }

        ReportItem::where('id', $pk)->update([$col => $value]);

        return response()->json(true, 200);
    }

    public function getReportitemForm(Request $request)
    {
        if ($request->mode == 'edit') {
            $Reportitem = ReportItem::find($request->id);
        } elseif ($request->mode == 'copy') {
            $Reportitem = ReportItem::find($request->id)->replicate();
            $this->_append_mismatched_times($Reportitem->report, $Reportitem);
        } else {
            $Reportitem = new ReportItem;
            $Reportitem->report_id = $request->report_id;
            $Reportitem->status = 'P';
            $this->_append_mismatched_times($Reportitem->report, $Reportitem);
        }

        $this->_validate_lock($Reportitem);

        $data['Reportitem'] = $Reportitem;
        $data['Report'] = $data['Reportitem']->report;
        $this->_append_form_variables($data);
        $data['route'] = $Reportitem->id ? 'trainee.report.updateReportitem' : 'trainee.report.storeReportitem';

        //Remove permission option, if there is no permission on that day
        $userpermission = $data['Report']->employee->userpermissions()->pendingApproved()->where('date', $data['Report']->date)->exists();

        if($userpermission == false){
            unset($data['technology_dropdown'][Technology::PERMISSION_UUID]);
        }

        return view('trainee.reports.partials.reportform', $data);
    }

    public function releaselockbreak(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, ['reason' => 'required']);
            foreach ($request->release_request as $release => $value) {
                if($value == NULL){
                    $reportitems = ReportItem::find($release);
                    $reportitems->release_request = 1;
                    $reportitems->save();
                }
            }
            $reports = Report::find($request->id);
            $reports->lock_reason = $request->reason;
            $reports->save();
            $reports->releaseLockMail();
        } else {
            $data['releaselocks'] = ReportItem::where('report_id', '=', $request->report_id)->locked()->whereNull('release_request')->get();
            return view('trainee.reports.partials.releaselock_break', $data);
        }
    }
    
    public function getgithubCommits(Request $request)
    {
        $data['userSettings'] = UserSettings::where('user_id', \Auth::user()->id)->where('name', UserSettings::GITHUB_CREDENTIALS )->first();
        $data['values'] = json_decode($data['userSettings']->value, true);
        if(!empty($data['values']['username'])){
            $data['github'] = new GithubHelper();
            $data['githubDetails'] = $data['github']->getUserLatestCommits();

            if(isset($data['githubDetails']->errors)){
                $message = implode(',', array_map(function ($err){
                    return $err->message;
                }, $data['githubDetails']->errors));

                return response()->json(['message' => $message], 422);
            }
            $data['githubItems'] = [];
            if($data['githubDetails']->items){
                $items = collect($data['githubDetails']->items);
                $data['githubItems'] = $items->map(function($item){
                    return ['date'=> $item->commit->committer->date,'username'=>$item->commit->committer->name,'message'=>$item->commit->message ,'repository'=>$item->repository->name];
                });
            }
        }
        
        return view('trainee.reports.partials.github_latest_commits', $data);
    }

    protected function _save($request)
    { 
        $Report = Report::find($request->id);
       
        $this->validate($request, [
            'id' => new ReportValidator($Report)
        ]);

       // $this->checkWorkingHours($Report);

        $endtime = Carbon::parse($Report->lastreportitem()->end)->toTimeString();
        $Report->end = $endtime;
        $Report->status = 'S';
        $Report->save();

        //$this->checkReportForCompensation($Report);

        $Report->mailTo();
        Mail::to($Report->employee->email)->queue(new ReportsMail($Report));

        Entry::query()->where('date', $Report->date)->update(['end' => $endtime]);

        flash('Report sent successfully.')->success();
        return redirect()->route('trainee.report.index');
    }

    protected function checkReportForCompensation($Report)
    {
        $holidays = Holiday::pluck('date')->toArray();
        $date = $Report->date;
        //If report date is holiday or Sunday
        if (in_array($date, $holidays) || Carbon::parse($date)->dayOfWeek == 7 || Setting::isOfficialLeaveToday($date)) {
            $office_timing = @$Report->employee->officetiming;

            if ($timings = @$office_timing->value) {
                if ($Report->workedhours >= $office_timing->totalWorkingHours) {
                    //if worked more than working hours
                    $days = 1;
                    $type = 'L';
                } else if ($Report->workedhours >= $timings->half_day_hours) {
                    //if worked more than half a day
                    $days = 0.5;
                    $type = 'L';
                } else if ($Report->workedhours >= $timings->permission_hours) {
                    //if worked more than permission hours
                    $days = 1;
                    $type = 'P';
                } else {
                    return;
                }

                $compensation = new Compensation();
                $compensation->employee_id = $Report->employee_id;
                $compensation->date = $date;
                $compensation->reason = "Compensation for working on {$date}";
                $compensation->days = $days;
                $compensation->type = $type;
                $compensation->save();
            }
        }
    }

    protected function checkWorkingHours($Report)
    {
        $worked_hours = $Report->workedhours;
        $timings = $Report->employee->officetiming;
        $timing_value = $timings->value;
        $official_worked_hours = $timings->totalWorkingHours;
        $permission_hours = $timings->value->permission_hours; //Todo: when report send have to get the correct permission max. hours

        $lastreport = $Report->lastreportitem();
        $actualEndtime = Carbon::parse($Report->actualEndTime)->toTimeString();
        $without_permission_hours = AppHelper::getTimeDiffFormat($permission_hours,$official_worked_hours);
        
        //If late entry on official permission day
        if(Setting::isOfficialPermissionToday($Report->date)){
            $late_entry = LateEntry::whereDate('date', $Report->date)->exists();

            if($late_entry){
              
                $office_perm_worktime_seconds = AppHelper::getSecondsFromTime($timing_value->off_perm_work_time.':00');
                $less_time_seconds = AppHelper::getSecondsFromTime($timing_value->rp_grace.':00');

                $worked_hours_seconds = $office_perm_worktime_seconds - $less_time_seconds;
                $official_worked_hours = AppHelper::secondsToHours($worked_hours_seconds);
            }
        }

        if(strtotime($lastreport->end) < strtotime($actualEndtime)){
            if ((strtotime($worked_hours) < strtotime($official_worked_hours))) {
                // (8:00 < 8:10)
                $entryHelper = new EntryHelper($Report->employee, Carbon::parse($Report->date));

                if ($worked_hours >= $without_permission_hours) {
                    // 7:00 >= (8:10 - 2:00 == 6:10)
                    $entryHelper->addPermission($lastreport->end, $actualEndtime);
                } else {
                    $entryHelper->addHalfDay();
                }
            }
        }
    }

    public function deleteReportitems(Request $request, ReportItem $reportitem)
    {
        $Reportitem = $reportitem::find($request->id);
        $this->_validate_lock($Reportitem);
        $Reportitem->delete();
        return response()->json(true, 200);
    }

    protected function _validate_lock($Reportitem)
    {
        abort_if(@$Reportitem->lock == 1, 403, 'Access Denied');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
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

    public function timeronrequest(Request $request)
    {
        $model = new Report();

        $request->validate([
            'date' => @$request->rep_date ? 'required':'',
            'start' => 'required',
            'reason' => 'required',
        ]);

        $this->validateEntry($request);
 
        $manualStartValue = Setting::where('name','MANUAL_REPORT_AUTOSTART')->first()->value;
        $model->employee_id = \Auth::user()->employee->id;
        $model->date = $request->date ?: date('Y-m-d');
        $model->start = $request->start;
        $model->reason = $request->reason;
        $model->manual_request_time = Carbon::now()->format('H:i:s');
        $model->status = $manualStartValue == 1 ? 'R' : 'P';
        $model->save();
        flash('Report request sent successfully')->success();
        return redirect()->back();
    }

    public function validateEntry(Request $request)
    {
        $timing = \Auth::user()->employee->officetiming->value;
        $carbon = Carbon::parse($request->start);
        $entryHelper = new EntryHelper(\Auth::user()->employee, $carbon);
       
        if (strtotime($request->start) > strtotime($timing->lt_half_day_excuse)) {
            $entryHelper->addHalfDay();
        } else if (strtotime($request->start) > strtotime($timing->lt_perm)) {
            $entryHelper->addPermission();
        } else if (strtotime($request->start) > strtotime($timing->lt_start)) {
            $entryHelper->addLateEntry($timing);
        }
    }

    public function show(Report $Report)
    {
        $data['Report'] = $Report;
        $data['Reportitems'] = $data['Report']->my_report_items();

        return view('trainee.reports.view', $data);
    }

    public function edit($id)
    {
        $data['Report'] = Report::find($id);
        $this->_append_form_variables($data);

        $employee = \Auth::user()->employee;
        $day = intval(Carbon::parse($data['Report']->date)->format('d')); 
        $data['report_sort'] = @$employee->officetiming->getSlotByDay($day)->report_sort;
        $data['formUrl'] = route('trainee.report.update', $data['Report']->id);
        $data['formMethod'] = 'PUT';

        return view('trainee.reports.edit', $data);
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
        $model = ReportItem::find($id);
        $this->_validate_reportitem($request);
        $this->_save_reportitem($request, $model);
        return response()->json(['success' => 'Success!', 'id' => $id], 200);
    }
    
    // public function setPermissionTime(Request $request)
    // {
    //     if($request->permission == Technology::PERMISSION_UUID){
    //         $report = Report::find($request->report_id);
    //         $userpermission = Userpermission::where('employee_id', $report->employee_id)->where('date', $report->date)->first();
    //             if(!empty($userpermission)){
    //                 $start = Carbon::parse($userpermission->start)->format('H:i');
    //                 $end = Carbon::parse($userpermission->end)->format('H:i');
    //                 return response()->json(['success' => 'Success', 'start' => $start , 'end' => $end ], 200);
    //             }
    //     }
    // }

    // public function getDailyMonthlyReport(Request $request)
    // {
    //     $data['request'] = $request;
    //     $data['employees'] = [\Auth::user()->employee];

    //     return view('employee.reports.monthlyreports.daily', $data);
    // }

    // public function getmonthlyreportitems(Request $request)
    // {
    //     $employee = \Auth::user()->employee;
    //     $EmployeeHelper = new EmployeeHelper($employee);
    //     $data = $EmployeeHelper->getMonthlyReportitemsData($request);
    //     return view('layouts.partials.monthlyreports.dailyitems', $data, compact('employee'));
    // }

    // public function getLeaveMonthlyReport(Request $request)
    // {
    //     $data['request'] = $request;
    //     $data['employees'] = [\Auth::user()->employee];
    //     $month_year = Carbon::parse($request->month_year);
    //     $data['year'] = $month_year->year;
    //     $data['month'] = $month_year->month;
    //     $data['yearly'] = '';

    //     return view('employee.reports.monthlyreports.leave', $data);
    // }

    // public function getleaveitems(Request $request)
    // {
    //     $EmployeeHelper = new EmployeeHelper(\Auth::user()->employee);
    //     $data = $EmployeeHelper->getMonthlyLeaveReportitemsData($request);

    //     return view('layouts.partials.monthlyreports.leaveitems', $data);
    // }
    
    // public function getLeaveYearlyReport(Request $request)
    // {
    //     $data['request'] = $request;
    //     $data['employees'] = [\Auth::user()->employee];
    //     $data['year'] = $request->month_year;
    //     $data['month'] = '';
    //     $data['yearly'] = 'yearlyLeaves';

    //     return view('employee.reports.yearlyreports.leave', $data);
    // }

    // public function getyearlyleaveitems(Request $request)
    // {
    //     $EmployeeHelper = new EmployeeHelper(\Auth::user()->employee);
    //     $data = $EmployeeHelper->getMonthlyLeaveReportitemsData($request);
        
    //     return view('layouts.partials.monthlyreports.leaveitems', $data);
    // }

    // public function getMonthlyAssessmentReport(Request $request)
    // {
    //     $data['request'] = $request;
    //     $data['employees'] = [\Auth::user()->employee];

    //     if($request->has('month_year')){
    //         $dateObj = \Carbon\Carbon::createFromFormat('Y-m-d', $request->month_year.'-01');
    //     }else{
    //         $dateObj = \Carbon\Carbon::now();
    //     }

    //     $data['start'] = $dateObj->startOfMonth()->toDateString();
    //     $data['end'] = $dateObj->endOfMonth()->toDateString();
        
    //     return view('employee.reports.assessment', $data);
    // }

    // public function getMonthlyBreakTimingsReport(Request $request)
    // {
    //     $data['request'] = $request;
    //     $data['employees'] = [\Auth::user()->employee];

    //     if($request->has('month_year')){
    //         $dateObj = \Carbon\Carbon::createFromFormat('Y-m-d', $request->month_year.'-01');
    //     }else{
    //         $dateObj = \Carbon\Carbon::now();
    //     }

    //     $data['start'] = $dateObj->startOfMonth()->toDateString();
    //     $data['end'] = $dateObj->endOfMonth()->toDateString();
        
    //     return view('employee.reports.breaktimings', $data);
    // }

    // public function employeeMonthlyBreaks(Request $request) 
    // {
    //     $query = Report::where('employee_id', $request->employee_id)->whereBetween('date' , [$request->start , $request->end])->oldest('date'); 

    //     $data['reports'] = $query->get();

    //     $no_reports = $query->noreport()->pluck('id')->toArray();

    //     $noReport = collect([]);
       
    //     foreach($data['reports'] as  $key => $report){
    //         if(!in_array($report->id, $no_reports)){

    //             $org_break =$report->employee->officetiming->value->break_hours.':00';
    //             $breakSeconds = AppHelper::getSecondsFromTime($org_break);
            
    //             $break  = Carbon::parse($report->breakhours)->format('H:i').':00';
    //             $exceed = $break > $org_break; 
    //             $less_break =  $break < $org_break;
            
    //             if($exceed){
    //                 $exceed_seconds = AppHelper::getSecondsFromTime($break); 
    //                 $break_exceedSeconds = $breakSeconds * $exceed; 
            
    //                 $diff = $exceed_seconds - $break_exceedSeconds; 
    //                 $report->ExceedBreak = AppHelper::secondsToHours($diff);  
    //             }else{
    //                 $less_seconds = AppHelper::getSecondsFromTime($break); 
    //                 $break_lessSeconds = $breakSeconds *  $less_break; 
    //                 $diff =   $break_lessSeconds - $less_seconds; 
    //                 $report->lessBreak = AppHelper::secondsToHours($diff);
    //             }
    //             $data['noReport'] = $noReport->push($report); 
    //         }
    //     }
          
    //     return view('admin.employees.monthlybreaks', $data);
    // }
    
    // public function getMonthlyWorkingHoursReport(Request $request)
    // {
    //     $data['request'] = $request;
    //     $data['project'] = Project::getProjectReportDataByEmployees(\Auth::user()->employee->id, $request);
    //     return view('employee.reports.project', $data);
    // }

    // public function showMonthlyWorkingHoursReport(Request $request)
    // {
    //     return view('layouts.partials.projectitemstable', compact('request'));
    // }

    public function createprojectname(Request $request)
    {
        $this->validate($request, [
            'name' => "required|unique:projects,name,NULL,deleted_at",
            'confirm_project_name' => 'nullable|same:name'
        ]);

        if (!$request->confirm_project_name) {
            $similarity = Project::whereRaw("name SOUNDS LIKE '{$request->name}'")->pluck('name')->toArray();

            if ($similarity) {
                return response()->json(['similarity' => $similarity, 'success' => false]);
            }
        }
        
        $project = Project::firstOrCreate([
             'name' => $request->name
         ]);

        return response()->json(['success' => true, 'message' => 'Record is successfully added','project'=>$project]);
    }

    public function saveOrder(Request $request)
    {
        $order_ids = $request->id;

        foreach($order_ids as $x => $id)
        {
            $report = ReportItem::find($id);
            $report->order = $x+1;
            $report->save();
        }

        return response()->json(true);
    }

    //Report Extend Form
    public function extendhours(Request $request){
        if ($request->isMethod('post')) {
            //Report Extend Hours Store
            $data = $request->validate([
                'extend' => ['required','regex:/[a-z,\s]/'],
                'status' => 'required'
            ]);
            preg_match('/(?P<hour>\d+)h/',$data['extend'], $hour);
            preg_match('/(?P<min>\d+)m/',$data['extend'], $min);
            $hours = @$hour['hour'];
            $mins = @$min['min'];
            $time = Carbon::parse("00:00")->addHours($hours)->addMinutes($mins)->format('H:i');
            $report = Report::find($request->report_id);
            $reportitem = ReportItem::find($request->reportitem_id);
            $elapsedtime = $reportitem->getElapsedTime();
            $elaspsed_seconds = Carbon::parse("00:00")->diffInSeconds($elapsedtime);
            if($request->include_break == 1){
                $org_breakhours = $report->employee->officetiming->value->break_hours;
                $breakhours = Carbon::parse($report->breakhours)->format('H:i');
                $remaining_hours = Carbon::parse($org_breakhours)->diffInSeconds($breakhours);
                if($remaining_hours != 0){
                    $time = Carbon::parse($time)->addHours(gmdate('H',$remaining_hours))->addMinutes(gmdate('i',$remaining_hours))->format('H:i');
                } else if($remaining_hours == 0){
                    $break_seconds = Carbon::parse('00:00')->diffInSeconds($org_breakhours);
                    $worked_seconds = Carbon::parse('00:00')->diffInSeconds($report->workedhours);
                    $org_workhours = Carbon::parse($report->actualendtime)->format('H:i:s');
                    $total = Carbon::parse($org_workhours)->diffInSeconds($report->start);
                    $remaining_workhours = gmdate('H:i',$total - $break_seconds-$worked_seconds+$elaspsed_seconds);
                    if($remaining_workhours < $time){
                        $diffrence = Carbon::parse($time)->diffInSeconds($remaining_workhours);
                        $remaining_workhours = $total - $break_seconds -$worked_seconds ;
                        if($diffrence != 0)
                        {
                            $time = Carbon::parse($time)->subHours(gmdate('H',$diffrence))->subMinutes(gmdate('i',$diffrence))->format('H:i'); 
                        }
                    }
                }
            }
            $extend = ReportItem::where('report_id',$request->report_id)->orderBy('start','ASC')->get();
            $elapsed = Carbon::parse($elapsedtime)->format("H:i");
            $elapsedseconds = Carbon::parse("00:00")->diffInSeconds($elapsed);
            $extendtime = Carbon::parse($time)->subSeconds($elapsedseconds)->format('H:i');
            if($time>$elapsed){
                foreach($extend as $k => $report)
                {
                    $extendreport = $reportitem->replicate();
                    if(isset($extend[$k+1])){
                        $diff = Carbon::parse($report->end)->diffInSeconds($extend[$k+1]->start);
                        $time = gmdate('H:i',$diff);
                        $hours = gmdate('H',$diff);
                        $mins = gmdate('i',$diff);
                        if($diff != 0){
                            if($extendtime >= $time){
                                $extendreport->start = $report->end;
                                $extendreport->end = $extend[$k+1]->start;
                                if(($data['status'] == 'C')&&($extendtime > $time)){
                                    $extendreport->status = 'P';
                                } else {
                                    $extendreport->status = $data['status'];
                                }
                                $extendreport->save();
                                $extendtime = Carbon::parse($extendtime)->subHours($hours)->subMinutes($mins)->format('H:i');
                            } else if(($extendtime < $time) && ($extendtime != "00:00")){
                                $seconds = Carbon::parse("00:00")->diffInSeconds($extendtime);
                                $hours = gmdate('H',$seconds);
                                $mins = gmdate('i',$seconds);
                                $end = Carbon::parse($report->end)->addHours(@$hours)->addMinutes(@$mins)->format('H:i:s');
                                $extendreport->start = $report->end;
                                $extendreport->end = $end;
                                $extendreport->status = $data['status'];
                                $extendreport->save();
                                break;
                            } 
                        } 
                    } else {
                        if($extendtime != "00:00"){
                            $hours = Carbon::parse($extendtime)->format('H');
                            $mins = Carbon::parse($extendtime)->format('i');
                            $end = Carbon::parse($report->end)->addHours($hours)->addMinutes($mins)->format('H:i:s');
                            $extendreport->start = $report->end;
                            $extendreport->end = $end;
                            $extendreport->status = $data['status'];
                            $extendreport->save();
                        }
                    } 
                }
                return response()->json(true);
            } else {
                return response()->json(['message' => 'The give invalid', 'errors' => ['id' => ['Extend Time should  must be greater than Elapsed ']]], 422);
            }
        }       
        $data['reportitem'] = ReportItem::find($request->reportitem_id);
        $data['status'] = ReportItem::$status;
        return view('trainee.reports.partials.extend',$data);
    }
}
