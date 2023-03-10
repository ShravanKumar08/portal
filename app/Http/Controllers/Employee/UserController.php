<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\UsersDataTable;
use App\Helpers\CustomfieldHelper;
use App\Helpers\EmployeeHelper;
use App\Helpers\GithubHelper;
use App\Helpers\JiraHelper;
use App\Http\Controllers\Controller;
use App\Mail\ComposeMail;
use App\Models\Employee;
use App\Models\Officetiming;
use App\Models\Setting;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Userpermission;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inani\Larapoll\Poll;
use Validator;
use function back;
use function dd;
use function flash;
use function redirect;
use function response;
use function str_random;
use function view;
use Carbon\Carbon;
use App\Helpers\AppHelper;

class UserController extends Controller {

    public function dashboard() {
        $data = [];
//        $data['jira'] = new JiraHelper();
//        $data['Jii'] = $data['jira']->callAPI('user');
//        dd($data['Jii']);
//        $github = new GithubHelper();
//        dd($github->getUserLatestCommits());

//        $data['hide_breadcrumb'] = true;
//        $data['ContainerClass'] = '';

        return view('employee.dashboard', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(UsersDataTable $dataTable) {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(User $user) {
//        $Model = $user;
//        return view('admin.users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(User $user, Request $request) {
        $this->_validate($request);

        $password = str_random(15);
        $request->request->add(['password' => $password]);
        $user->fill($request->all())->save();

        flash('User created successfully')->success();
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(User $user) {
//        $Model = $user;
//        return view('admin.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, User $user) {
//        dd($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    private function _validate($request, $id = null) {
        return $this->validate($request, [
                    'name' => 'required',
                    'email' => "required|email|unique:users,email,{$id},id,deleted_at,NULL",
        ]);
    }

    public function myprofile(Request $request) {
        
         if ($request->isMethod('post')) {
            $this->validate($request, Employee::getProfileRules());
            \Auth::user()->employee->saveProfile($request);

            flash('Profile updated successfully')->success();
            return redirect()->back();
        } else {
            $employeeHelper = new EmployeeHelper(\Auth::user()->employee);
            $data = $employeeHelper->getEmployeeProfileFormData();

            return view('employee.my_profile', $data);
        }
        
    }
    
    public function changepassword(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), User::getChangePasswordRules());
            
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(['tab'=>'changepassword']);
            }
           
            \Auth::user()->saveChangepassword($request);
             
            flash('Your password has been updated.')->success();
            return redirect()->back()->withInput(['tab'=>'changepassword']);
            
        } else {
            return view('employee.my_profile');
        }
    }

    public function calendar_events(Request $request) {
       $eventsValue =  AppHelper::calendarDashboard($request, 'employee');
       return response()->json($eventsValue);
    }
    
    public function polls()
    {
        $polls = Poll::all();

        return view('larapoll::dashboard.home', compact('polls'));
    }

    public function vote($id)
    {
        $model = Poll::findOrFail($id);

        return view('larapoll::dashboard.vote', compact('model'));
    }
    
    public function composemail(Request $request) {
        if($request->isMethod('post')) {
            if($request->hasFile('file')) {
                $files = Storage::disk('public')->putFile('uploads', $request->file('file'));
                
                return response()->json(['success' => 'Success!', 'filename' => $files], 200);
            } else {
                $composemail = $request->except('_token');
                $emails = explode(',', $composemail['to']);
                Mail::to($emails)->send(new ComposeMail($composemail));      
                flash('Your Mail Sent')->success();
                return redirect()->back();
            }
        } else {
            $email_templates = Setting::emailTemplate(1)->pluck('name', 'value')->toArray();
            return view('employee.composemail', compact('email_templates'));
        }        
    }
    
    public function composeFileDelete(Request $request) {
        Storage::disk('public')->delete($request->filename);
        
        return response()->json(['success' => 'Success!', 'filename' => $request->filename], 200);
    }
    
    public function searchuseremail() {
        return response()->json(User::all());
    }
}
