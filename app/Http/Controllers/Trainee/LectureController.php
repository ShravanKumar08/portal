<?php

namespace App\Http\Controllers\Trainee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\LectureDataTable;
use App\Models\Report;
use App\Models\Lecture;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use App\Mail\LectureNotification;
use App\Scopes\EmployeeScope;
use Illuminate\Support\Facades\Mail;
use App\Mail\LectureJoinNotification;
use DB;

class LectureController extends Controller
{
    public function create()
    {
        $designationList = Employee::oldest('name')->active()->pluck("designation_id")->toArray();
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['designation'] = Designation::whereIn('id',$designationList)->pluck('name', 'id')->toArray();
        return view('trainee.lectures.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'start' => 'required',
            'end' => 'required',
            'description' => 'required',
            'employees' => 'required'
        ]);
        $employee_id = \Auth::user()->employee->id;
        $lecture = new Lecture();
        $lecture->employee_id = $employee_id;
        $lecture->fill($data);
        $lecture->save();
        $result = $data['employees'];
        // unset($result[array_search($employee_id, $result)]);
        $lecture->employees()->attach($result, ['status' => 'P']);
        $to_address = $lecture->employees->pluck('user.email')->toArray();
        Mail::to($to_address)->queue(new LectureNotification($lecture));
        return redirect('trainee/lectures?scope=Self');
    }

    public function index(LectureDataTable $dataTable, Request $request, Report $report)
    {
        $dataTable->role = "trainee";
        $data['statuses'] = $report::$status;
        $data['request'] = $request;
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('trainee.lectures.index', $data);
    }

    public function show($id)
    {
        $lecture = Lecture::withoutGlobalScope(EmployeeScope::class)->find($id);
        $lecture['name'] = $lecture->employee->name;
        return view('trainee.lectures.view', $lecture);
    }

    public function edit($id)
    {
        $data['lecture'] = Lecture::find($id);
        $data['id'] = $id;
        $designationList = Employee::oldest('name')->active()->pluck("designation_id")->toArray();
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $data['designation'] = Designation::whereIn('id',$designationList)->pluck('name', 'id')->toArray();
        $data['employee'] = $data['lecture']->employees;
        return view('trainee.lectures.edit', $data);
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

        return redirect('trainee/lectures?scope=Self');
    }

    public function deleteLecture(Request $request)
    {
        $lecture = Lecture::find($request->id);
        $lecture->delete();
        $lecture->employees()->detach();
        return redirect('trainee/lectures?scope=Self');
    }

    public function status(Request $request)
    {
        $this->validate($request,[
            'id' => 'required',
            'active' => 'required',
        ]);

        $employee_id = \Auth::user()->employee->id;
        $status = $request->active;

        $lecture = Lecture::withoutGlobalScope(EmployeeScope::class)->findOrFail($request->id);

        $hasEmployee = $lecture->employees()->where('id', $employee_id)->exists();

        if(!$hasEmployee){
            $lecture->employees()->attach($employee_id, ['status' => $status]);
        }else{
            $lecture->employees()->updateExistingPivot($employee_id, ['status' => $status]);
        }

        if($status == 'A'){
            Mail::to($lecture->employee->user->email)->queue(new LectureJoinNotification($lecture));
        }

        return redirect('trainee/lectures?scope=Others');
    }

    public function list($id)
    {
        $data['lecturer_id'] = Lecture::withoutGlobalScope(EmployeeScope::class)->find($id);
        $data['lectures'] = $data['lecturer_id']->employees->sortBy('name');
        $data['total'] = $data['lectures']->count();
        $status = $data['lectures']->pluck('pivot')->pluck('status')->all();
        $count = array_count_values($status);
        $data['approved'] = @$count['A'];
        $data['pending'] = @$count['P'];
        $data['declined'] = @$count['D'];
        return view('trainee.lectures.list', $data);
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
