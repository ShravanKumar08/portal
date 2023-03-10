<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ScheduleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Employee;
use App\Models\Officetiming;
use App\Models\Officetimingslot;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Redirect;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(ScheduleDataTable $dataTable , Schedule $schedule , Request $request)
    {
        $data['request'] = $request;
           
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.schedules.index' , $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Schedule $schedule, Request $request)
    {
        return view('admin.schedules.create', compact('request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Schedule $schedule, Request $request)
    {
        $model = new Schedule(['schedule_date' => $request->date , 'key' => $request->type]);
       
        if($request->type == 'OFFICIAL_PERMISSION_LEAVE_DAYS'){
            $setting = Setting::where('name' , $request->type)->first();
            $model->fill(['value' => [
                'value' => $setting->value
            ]]);
            $future_setup = $setting->schedules()->save($model);

            return redirect()->route('schedule.edit', $future_setup->id);
        }elseif($request->type == 'TRAINEE_TO_PERMANENT'){
            $employee = Employee::find($request->model_id);
            $model->fill(['value' => [
                'employeetype' => 'P'
            ]]);
            $employee->schedules()->save($model);

            return redirect()->route('schedule.index');
        }else{
            $office_timing = Officetiming::find($request->slot_id); 
            $model->fill(['value' => [
                'employee_id' => [],
                'slots' => $office_timing->slots
            ]]);
            $timings = $office_timing->schedules()->save($model);
            return redirect()->route('schedule.edit',  $timings->id);
        }
    }

        /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Schedule $schedule)
    {
        $data['Model'] = $schedule;
        $data['slots'] = Officetimingslot::oldest('name')->pluck('name', 'id')->toArray();
        if ($schedule->key == 'OFFICIAL_PERMISSION_LEAVE_DAYS') {
            $data['numberFormatter'] = new \NumberFormatter('en_US', \NumberFormatter::ORDINAL); 
        }elseif($schedule->key == 'OFFICE_TIMING_SLOT'){
            $data['Employee'] = Employee::where('officetiming_id', $schedule->model_id)->get();
            $data['Employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
            $data['slots'] = Officetimingslot::oldest('name')->pluck('name', 'id')->toArray();
        }elseif($schedule->key == 'TRAINEE_TO_PERMANENT'){
            $data['trainees'] = Employee::query()->trainee()->active()->oldest('name')->pluck('name', 'id');
        }
        
        return view('admin.schedules.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $schedule->fill($request->except(['_method', '_token' ]));
        $schedule->save();
        flash('Schedule Alloted successfully')->success();
        return redirect()->route('schedule.index');  

    }

    public function slotSave(Request $request)
    {
        $schedule = Schedule::find($request->schedule_id);
        $value = $schedule->value; 
        $explode = explode('-', $request->day);
        $count = count($explode);

        if ($count > 1) {
            $array = range($explode[0], $explode[1]);
            foreach ($array as $arr) {
                $value['slots'][$arr] = $request->slot; 
            }
        } else {
            $value['slots'][$request->day] = $request->slot;
        }

        $schedule->value = $value;
        $schedule->save();

        return response()->json(['slot' => $request->slot, 'day' => $request->day]);
    }

    public function slot_events(Request $request)
    {
        $officetiming = Schedule::find($request->officetiming_id);
        return response()->json($this->_get_slot_events($officetiming));
    }

    protected function _get_slot_events($officetiming)
    {
        $events = [];
        $officetimingslots =  $officetiming->value['slots']; 
        foreach ($officetimingslots as $day => $officetimingslot) {
            $slot = Officetimingslot::find($officetimingslot);
            $events[] = [
                'title' => $slot->name,
                'id' => $slot->id,
                'start' => Carbon::now()->year . '-' . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT),
                'bg_color' => $slot->bg_color,
                'text_color' => $slot->text_color,
            ];
        }

        return $events;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Schedule $Schedule) {
        $Schedule->delete();
    }

}
