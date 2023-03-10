<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OfficetimingslotDataTable;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Officetiming;
use App\Models\Officetimingslot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;

class OfficetimingslotController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(OfficetimingslotDataTable $dataTable, Officetiming $Officetiming )
    {
        $data['officetimings'] = $Officetiming->pluck("slots", "id")->toArray();
            
        $data['slots'] = Officetimingslot::all();
        //dd($data['officetimings']);
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.officetimingslots.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        if ($request->has('id')) {
            $data['Model'] = Officetimingslot::find($request->id)->replicate();
            $data['Model']->name = $data['Model']->name . " (copy)";
        }
       
        $data['Employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        $this->_append_form_variables($data);
        return view('admin.officetimingslots.create', $data);
    }

    protected function _append_form_variables(&$data)
    {
        $data['timings'] = Officetimingslot::$timings;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, Officetimingslot $Officetimingslot)
    {
        $slot_id = $this->_save($request, $Officetimingslot);
        if($request->new_timing == 1){
                $officetiming = new Officetiming;
                $officetiming->name = $request->name;
                $slots = [];
                foreach(range(1, 31) as $day){
                    $slots[$day] = $slot_id;
                }
                $officetiming->slots = $slots;
                $officetiming->save();
                if ($request->employee_ids) {
                     $employees = Employee::whereIn('id', $request->employee_ids)->get();
                     foreach ($employees as $employee) {
                        $employee->officetiming_id = $officetiming->id;
                        $employee->save();
                    }
               }
               flash('Office timings created and Assign employee successfully')->success();
               return redirect()->route('officetimingslot.index');;
        }
        flash('Office timing slot created successfully')->success();
        return redirect()->route('officetimingslot.index');
    }

    private function _save($request, $Officetimingslot)
    {
        $data = $request->except(['value']);
        $data['value'] = $request->value;
        $Officetimingslot->fill($data);
        $Officetimingslot->save();
        return $Officetimingslot->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Officetimingslot $Officetimingslot)
    {
        $data['Model'] = $Officetimingslot;
        $data['values'] = $data['Model']->value;
        return view('admin.officetimingslots.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Officetimingslot $Officetimingslot)
    {
        $data['Model'] = $Officetimingslot;
        $this->_append_form_variables($data);
        return view('admin.officetimingslots.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, Officetimingslot $Officetimingslot)
    {
        $this->_save($request, $Officetimingslot);

        flash('Office timing slot Updated successfully')->success();
        return redirect()->route('officetimingslot.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Officetimingslot $Officetimingslot)
    {
        $timing_query = Officetiming::where('slots', 'like', "%{$Officetimingslot->id}%");

        if ($timing_query->exists()) {
            return response()->json(['message' => 'This slot has an official timings (' . $timing_query->get()->implode('name', ', ') . ')'], 422);
        }

        $Officetimingslot->delete();
    }

}
