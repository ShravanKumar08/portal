<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EmployeeDataTable;
use App\Helpers\CustomfieldHelper;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Officetiming;
use App\Models\Permission;
use App\Models\User;
use App\Models\Setting;
use App\Models\Report;
use App\Models\Entry;
use App\Models\IDP;
use App\Models\UserSettings;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use function bcrypt;
use function flash;
use function redirect;
use function response;
use function view;
use DB;
use Illuminate\Database\Eloquent\Builder;


class EmployeeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(EmployeeDataTable $dataTable, Request $request) {
//        if (request()->scope == 'inactive' ) {
//                $designations = Designation::oldest('name')->get()->filter(function ($designation){
//                return $designation->inactive_employee_count;
//            });
//        }else{
//                $designations = Designation::oldest('name')->get()->filter(function ($designation){
//                return $designation->active_employee_count;
//            });
//        }
//        $data['designations'] = Designation::oldest('name')->active()->pluck("name", "id")->toArray();
        $designations = Designation::oldest('name')->active()->pluck("name", "id")->toArray();     
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.employees.index', compact('designations', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    protected function _append_form_variables(&$data) {
        $data['types'] = Employee::$types;
        $data['gender'] = Employee::$gender;
        $data['isLeads'] = Employee::$options;
        $data['custom_fields'] = CustomfieldHelper::getCustomfieldsByModule(Employee::class);
        $data['officetimings'] = Officetiming::oldest('name')->pluck("name","id")->toArray();
    }

    public function create(User $user) {
        $data['Model'] = $user;
        $data['Model']->active = 1;
        $this->_append_form_variables($data);
        return view('admin.employees.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, Employee $employee) {
        $this->_validate($request);

        $this->_save($request, $employee);

        flash('User created successfully')->success();
        return redirect('admin/employee');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Employee $employee) {
        $data['Employee'] = $employee;
        $data['Employee']->appendCustomFields();
        $data['User'] = $data['Employee']->user;
        $data['Designation'] = $data['Employee']->designation;
        $this->_append_form_variables($data);

        return view('admin.employees.view', $data);
    }

    public function access($id) {
        $data['Model'] = Employee::find($id);
        $data['Permissions'] = Permission::all()->groupBy(function ($permission) {
            if(str_contains( $permission->name, '.')){
                $exp = explode('.', $permission->name);
                return $exp[0];
            }

            return 'basic';
        });
        $data['getallpermissions'] = $data['Model']->user->getAllPermissions()->pluck('name')->toArray();
        return view('admin.employees.access', $data);
    }

    public function access_store(Request $request, $id) {
        $Model = Employee::find($id);

        if($user = $Model->user){
            $permissions = $request->name ? explode(',', $request->name) : [];
            if(!empty($permissions)){
                $permissions[] = 'dashboard';
                $assign_role = 'super-user';
                if(@$assign_role && !$user->hasRole($assign_role)){
                    $user->assignRole($assign_role);
                }
            }else{
                $remove_role = 'super-user';
                if(@$remove_role && $user->hasRole($remove_role)){
                    $user->removeRole($remove_role);
                }
            }

            $user->syncPermissions($permissions);
            //$user->syncRoles(['super-user', 'employee']);

            flash('Changes updated successfully')->success();
        }else{
            flash('User not found')->error();
        }

        return redirect('admin/employee');
    }
    public function upcoming_birthday(EmployeeDataTable $dataTable, Request $request)
    {   
        $employeetype = $request['employeetype'];

        $emp_upcoming_birthday = DB::select("SELECT * FROM (
            SELECT em.name,em.dob,em.id,  DATEDIFF(DATE_FORMAT(em.dob,CONCAT('%',YEAR(CURDATE()),'-%m-%d')),CURDATE()) 
            AS no_of_days FROM employees em INNER JOIN users u  ON em.user_id=u.id WHERE u.active = 1 AND em.employeetype='{$employeetype}'  UNION
            SELECT em.name,em.dob,em.id,
            DATEDIFF(DATE_FORMAT(em.dob,CONCAT('%',(YEAR(CURDATE())+1),'-%m-%d')),CURDATE()) 
            AS no_of_days FROM employees em INNER JOIN users u  ON em.user_id=u.id WHERE u.active = 1 AND em.employeetype='{$employeetype}') AS 
            employees WHERE no_of_days >= 0 GROUP BY NAME ORDER BY no_of_days ASC");
       return view('admin.employees.birthday',compact('emp_upcoming_birthday'));
    }

    public function getbreakTimingReports(Request $request)
    {
        $data['request'] = $request;
        
        $query = Employee::oldest('name')->permanent()->where('officetiming_id','<>',"");
        if($request->inactive_employee == 0) {
            $query->active();
        }   

        if($emp = $request->employee_id)
        {
            $query->whereIn('id', $emp);
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
       
        return view('admin.employees.breaktimings', $data);
    }

    public function employeeMonthlyBreaks(Request $request , Employee $employee) 
    {
        $query = Report::where('employee_id', $request->employee_id)->whereBetween('date' , [$request->start , $request->end])->oldest('date'); 

        $query2 = clone $query;

        $noReportIds = $query2->noreport()->pluck('id')->toArray();

        $data['reports'] = $query->whereNotIn('id', $noReportIds)->get();

        $data['Employee'] = $employee->where('id', $request->employee_id)->first();

        foreach($data['reports'] as  $report)
        {
            $org_break =$report->employee->officetiming->value->break_hours.':00';
            $breakSeconds = AppHelper::getSecondsFromTime($org_break);
        
            $break  = Carbon::parse($report->breakhours)->format('H:i').':00';
            $exceed = $break > $org_break; 
            $less_break =  $break < $org_break;
    
            if($exceed){
                $exceed_seconds = AppHelper::getSecondsFromTime($break);
                $break_exceedSeconds = $breakSeconds * $exceed; 
        
                $diff = $exceed_seconds - $break_exceedSeconds; 
                $report->ExceedBreak = AppHelper::secondsToHours($diff);
            }else{
                $less_seconds = AppHelper::getSecondsFromTime($break); 
                $break_lessSeconds = $breakSeconds *  $less_break; 
                $diff =   $break_lessSeconds - $less_seconds; 
                $report->lessBreak = AppHelper::secondsToHours($diff);
            }
        }

        return view('admin.employees.monthlybreaks', $data);
    }


    public function getTraineebreakTimingReports(Request $request)
    {
        $data['request'] = $request;
        
        $query = Employee::query()->trainee()->active();

        if($emp = $request->employee_id)
        {
            $query->whereIn('id', $emp);   
        }

        $data['employees'] = $query->get();
        $query = Employee::query()->trainee()->active()->oldest('name');
       
        $data['all_trainees'] = $query->get(); 
        $data['trainees_list'] = $data['all_trainees']->pluck("name", "id")->toArray();
        
        if($request->has('month_year')){
            $dateObj = Carbon::createFromFormat('Y-m-d', $request->month_year.'-01');
        }else{
            $dateObj =Carbon::now();
        }

        $data['start'] = $dateObj->startOfMonth()->toDateString();
        $data['end'] = $dateObj->endOfMonth()->toDateString();

      
        return view('admin.employees.traineebreaktimings', $data);
    }

    public function TraineeMonthlyBreaks(Request $request) 
    {
        $data['entries'] = Entry::where('employee_id', $request->employee_id)->whereBetween('date' , [$request->start , $request->end])->oldest('date')->get();
       
        foreach($data['entries'] as  $entry)
        {
            $data['Employee'] = $entry->employee->where('id', $request->employee_id)->first(); 
            $org_break = $entry->employee->officetiming->value->break_hours;
            $entry->getEntryItems();

            if($entry->total_out_hours < $org_break ){
                $entry->unusedBreak = AppHelper::getTimeDiffFormat(Carbon::parse($org_break)->format('H:i'),Carbon::parse($entry->total_out_hours)->format('H:i') , 'H:i', 'H:i', false);
            }else{
                $entry->unusedBreak =  '-'; 
            }
            
            if($entry->total_out_hours != '0:00' && $entry->total_out_hours > $org_break ){
                $entry->exceedBreak = AppHelper::getTimeDiffFormat(Carbon::parse($entry->total_out_hours)->format('H:i'), Carbon::parse($org_break)->format('H:i'), 'H:i', 'H:i', false); 
            }else{
                $entry->exceedBreak =  '-'; 
            }
            
        }
       
        return view('admin.employees.traineemonthlybreaks' , $data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {
        $data['Employee'] = Employee::findEmployeeById($id);
        $data['User'] = $data['Employee']->user;
        $data['Designation'] = $data['Employee']->designation;
        $this->_append_form_variables($data);
        return view('admin.employees.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $this->_validate($request, $id, $request->uid);

        $model = Employee::where('user_id', '=', $request->uid)->first();
        $this->_save($request, $model);

        flash('User updated successfully')->success();
        return redirect('admin/employee');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Employee $employee, User $user) {
        $user->where(['id' => $employee->user_id])->delete();
        $employee->delete();
    }

    private function _validate($request, $id = null, $uid = null) {
        $rules = [
            'name' => 'required',
            'employeetype' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'email' => "required|email|unique:users,email,{$uid},id,deleted_at,NULL",
            'casual_count_this_year' => 'required|numeric',
            'photo' => 'image',
        ];

        if (!$id) { // On Create
            $rules['password'] = 'required';
        }

        //Custom fields validation
        // CustomfieldHelper::appendCustomModuleRules( Employee::class, $rules);

        $this->validate($request, $rules);
    }

    protected function _save($request, $model) {
        $user = $this->_save_user($request);
        $designation = $this->_save_designation($request);

        $model->user_id = $user->id;
        $model->designation_id = $designation->id;
        $model->officetiming_id = $request->officetiming_id;

        $model->saveProfile($request);
    }

    protected function _save_user($request)
    {
        $user = User::firstOrNew(['email' => $request->email]);
        $userdata = $request->only(['name', 'email', 'password','active', 'isTeamLead']);
        if (!empty($userdata['password'])) {
            $userdata['password'] = bcrypt($userdata['password']);
        } else {
            unset($userdata['password']);
        }
        $user->fill($userdata);
        $user->save();

        //Assign and remove roles
        if($request->employeetype == 'P'){
            $assign_role = 'employee';
            $remove_role = 'trainee';
        }else if($request->employeetype == 'T'){
            $assign_role = 'trainee';
            $remove_role = 'employee';
        }

        if(@$assign_role && !$user->hasRole($assign_role)){
            $user->assignRole($assign_role);
        }

        if(@$remove_role && $user->hasRole($remove_role)){
            $user->removeRole($remove_role);
        }
        //end

        return $user;
    }

    protected function _save_designation($request)
    {
        return Designation::firstOrCreate(['name' => $request->designation['name']]);
    }

    public function searchDesignation(Request $request, Designation $Designation) {
        $query = $request->get('term', '');

        $designation_names = $Designation->where('name', 'LIKE', '%' . $query . '%')->active()->get();

        $data = array();
        foreach ($designation_names as $designation_name) {
            $data[] = array('value' => $designation_name->name, 'id' => $designation_name->id);
        }

        return response()->json($data);
    }

    public function overridreportmail(Request $request,$user_id) 
    {
        $name = 'REPORT_NOTIFICATION_EMAIL';

        $UserSettings = UserSettings::firstOrNew([
            'name' => $name,
            'user_id' => $user_id
        ]);

        if ($request->post()) {
            $UserSettings->value = json_encode($request->value);
            $UserSettings->save();    
            flash('Report Notification Mail Overrided Successfully !')->success();
            return redirect()->route('employee.index'); 
        }

        $UserSettings->value = $UserSettings->value == null ? Setting::fetch($name) : json_decode($UserSettings->value, true);

        $data['Model'] = $UserSettings;

        return view('admin.employees.mailoverride', $data);

    }

    public function idps($id)
    {
        $data['model'] = IDP::where('employee_id', $id)->first();

        return view('admin.employees.idps', $data);
    }
  
}
