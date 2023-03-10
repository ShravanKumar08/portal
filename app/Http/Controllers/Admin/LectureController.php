<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\LectureDataTable;
use App\Mail\LectureNotification;
use App\Models\Report;
use App\Models\Lecture;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use DB;

class LectureController extends Controller
{
    public function create(Employee $Employee)
    {
        $data['Model'] = $Employee;
        $designationList = Employee::oldest('name')->active()->pluck("designation_id")->toArray();
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['designation'] = Designation::whereIn('id',$designationList)->pluck('name', 'id')->toArray();
        return view('admin.lectures.create', $data);
    }

    public function index(LectureDataTable $dataTable, Request $request, Report $report)
    {
        $dataTable->role = "admin";
        $data['statuses'] = $report::$status;
        $data['request'] = $request;
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.lectures.index', $data);
    }

    public function show($id)
    {
        $lecture = Lecture::find($id);
        $lecture['name'] = $lecture->employee->name;
        return view('admin.lectures.view', $lecture);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
            'employees' => 'required|array|min:1'
        ]);

        $lecture = new Lecture();
        $lecture->employee_id = $request->employee_id;
        $lecture->fill($data);
        $lecture->save();
        $result = $data['employees'];
        $lecture->employees()->attach($result, ['status' => 'P']);
        $to_address = $lecture->employees->pluck('user.email')->toArray();
        Mail::to($to_address)->queue(new LectureNotification($lecture));
        return redirect('admin/lectures');
    }

    public function edit($id)
    {
        $data['lecture'] = Lecture::find($id);
        $data['id'] = $id;
        $designationList = Employee::oldest('name')->active()->pluck("designation_id")->toArray();
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['designation'] = Designation::whereIn('id',$designationList)->pluck('name', 'id')->toArray();
        $data['employee'] = $data['lecture']->employees;
        $data['employee_id'] = $data['lecture']->employee_id;
        return view('admin.lectures.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
            'employees' => 'required|array|min:1',
        ]);
        $lecture = Lecture::find($id);
        $lecture->employee_id = $request->employee_id;
        $lecture->fill($data);
        $lecture->save();
        $result = $data['employees'];
        $oldParticipants = $lecture->employees()->get()->pluck('id')->toArray();
        $lecture->employees()->syncWithoutDetaching($result);

        $removedParticipants = array_diff($oldParticipants, $result);
        if(count($removedParticipants)){
            $lecture->employees()->detach($removedParticipants);
        }

        $newParticipants = array_diff($result ,$oldParticipants);
        if(count($newParticipants) > 0)
        {
            $to_address = Employee::whereIn('id', $newParticipants)->with(['user'])->get()->pluck('user.email')->toArray();
            Mail::to($to_address)->queue(new LectureNotification($lecture));
        }
        return redirect('admin/lectures');
    }

    public function form(Request $request)
    {
        $data['employee_id'] = $request->employee_id;
        $designationList = Employee::oldest('name')->active()->pluck("designation_id")->toArray();
        $data['employeeList'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['designation'] = Designation::whereIn('id',$designationList)->pluck('name', 'id')->toArray();
        return view('admin.lectures.partials.form', $data);
    }

    public function list($id)
    {
        $data['lecturer_id'] = Lecture::withoutGlobalScope(EmployeeScope::class)->find($id);
        $data['lectures'] = Lecture::find($id)->employees->sortBy('name');
        $data['total'] = $data['lectures']->count();
        $status = $data['lectures']->pluck('pivot')->pluck('status')->all();
        $count = array_count_values($status);
        $data['approved'] = @$count['A'];
        $data['pending'] = @$count['P'];
        $data['declined'] = @$count['D'];
        return view('admin.lectures.list', $data);
    }

    public function deleteLecture(Request $request)
    {
        $lecture = Lecture::find($request->id);
        $lecture->delete();
        return redirect('admin/lectures');
    }

    public function getEmployees(Request $request)
    {
        $employees = [];
        if($request->designation_id != null){
            $employees = Employee::whereIn('designation_id', $request->designation_id)->pluck('name', 'id')->toArray();
        }

        return response()->json($employees);
    }

    public function markAttendance(Request $request)
    {
        $query = DB::table('employee_lecture')->where('employee_id', $request->joiner_id)
        ->where('lecture_id',$request->lecture_id);
        
        if($request->joiner_id && ($request->attendance == 0 || $request->attendance == 1)){
            $query->update(['mark_attendance' => $request->attendance]);
            if($request->attendance == 1) {
                $query->update(['status' => 'A']);
            } else {
                $query->update(['status' => 'P']);
            }
        }else {
            return response()->json(['error' => 'something wrong!'], 400);
        }
    }

}
