<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\EmployeeHelper;
use Validator;
use Inani\Larapoll\Poll;

class TraineeController extends Controller
{
    public function dashboard()
    {
        $data = [];

        $data['hide_breadcrumb'] = true;
        $data['ContainerClass'] = '';
        $data['rules'] = \App\Models\Setting::fetch('COMPANY_RULES');

        return view('trainee.dashboard', $data);
    }
    
    public function myprofile(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, Employee::getProfileRules());
            \Auth::user()->employee->saveProfile($request);

            flash('Profile updated successfully')->success();
            return redirect()->back();
        } else {
            $employeeHelper = new EmployeeHelper(\Auth::user()->employee);
            $data = $employeeHelper->getEmployeeProfileFormData();

            return view('trainee.my_profile', $data);
        }
    }

    public function changepassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), User::getChangePasswordRules());
            
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(['tab'=>'changepassword']);
            }
           
            \Auth::user()->saveChangepassword($request);
             
            flash('Your password has been updated.')->success();
            return redirect()->back()->withInput(['tab'=>'changepassword']);
            
        } else {
            return view('trainee.my_profile');
        }
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
}
