<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OfficetimingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Officetiming;
use App\Models\Officetimingslot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;

class OfficetimingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(OfficetimingDataTable $dataTable)
    {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.officetimings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    //    public function create() {
    //        $this->_append_form_variables($data);
    //        return view('admin.officetimings.create', $data);
    //    }

    protected function _append_form_variables(&$data)
    {
        $data['slots'] = Officetimingslot::oldest('name')->pluck('name', 'id')->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Officetiming $Officetiming)
    {
        $Officetiming->name = $request->name;
        $Officetiming->employeetype = $request->employeetype;
        $Officetiming->save();

        flash('Office timings created successfully')->success();
        return redirect()->route('officetiming.edit', $Officetiming->id);
    }

    private function _save($request, $Officetiming)
    {
        $data = $request->only(['name']);
        $data['slots'] = $request->slots;
        $Officetiming->fill($data);
        $Officetiming->save();

        if (!$request->employee_id) {
            Employee::where('officetiming_id', $Officetiming->id)->update(['officetiming_id' => '']);
        } else {
            $employees = Employee::whereIn('id', $request->employee_id)->get();
            foreach ($employees as $employee) {
                $employee->officetiming_id = $Officetiming->id;
                $employee->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Officetiming $Officetiming)
    {
        $data['Model'] = $Officetiming;
        $data['values'] = $data['Model']->value;
        return view('admin.officetimings.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Officetiming $Officetiming)
    {
        $data['Model'] = $Officetiming;
        $data['Employee'] = Employee::where('officetiming_id', $Officetiming->id)->get();
        $data['Employees'] = Employee::oldest('name')->active()->where('employeetype',$Officetiming->employeetype)->pluck("name", "id")->toArray();
        $this->_append_form_variables($data);
        return view('admin.officetimings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Officetiming $Officetiming)
    {
        $this->_save($request, $Officetiming);

        flash('Office timings Updated successfully')->success();
        return redirect('admin/officetiming?scope='.$Officetiming->employeetype);
    }

    public function slotSave(Request $request)
    {
        $officetiming = Officetiming::find($request->officetiming_id);
        $slots = $officetiming->slots;

        $explode = explode('-', $request->day);
        $count = count($explode);

        if ($count > 1) {
            $array = range($explode[0], $explode[1]);
            foreach ($array as $arr) {
                $slots[$arr] = $request->slot;
            }
        } else {
            $slots[$request->day] = $request->slot;
        }

        $officetiming->slots = $slots;
        $officetiming->save();

        return response()->json(['slot' => $request->slot, 'day' => $request->day]);
    }

    public function slot_events(Request $request)
    {
        $officetiming = Officetiming::find($request->officetiming_id);
        return response()->json($this->_get_slot_events($officetiming));
    }

    protected function _get_slot_events($officetiming)
    {
        $events = [];
        $officetimingslots = $officetiming->slots;

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
     * @param  int  $id
     * @return Response
     */
    public function destroy(Officetiming $Officetiming)
    {
        if ($Officetiming->employees->count()) {
            return response()->json(['message' => 'This official timings has employees (' . $Officetiming->employees->implode('name', ', ') . ')'], 422);
        }

        $Officetiming->delete();
    }
}
