<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TempcardDataTable;
use App\Http\Controllers\Controller;
use App\Models\Tempcard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function flash;
use function redirect;
use function view;
use App\Models\Employee;
use App\Models\Report;

class TempcardController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(TempcardDataTable $dataTable) {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.tempcard.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Tempcard $Tempcard) {
        $data['Model'] = $Tempcard;
        $data['Model']->active = 1;
        $this->_append_form_variables($data);
        return view('admin.tempcard.create', $data);
    }

    protected function _append_form_variables(&$data) {
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request,Tempcard $Tempcard) {
        $this->_validate($request);
        
        $this->_save($request, $Tempcard);

        if($request->report_start != "") 
        {
            $report = Report::firstOrNew(['date' => $request->from, 'employee_id' => $request->employee_id]);
            $report->start = $request->report_start;
            $report->save();
        }
        
        $redirect = route('tempcard.index', ['employee_type' => $Tempcard->employee->employeetype]);
        if ($request->ajax()) {
            return response()->json(['success' => 'Success!'], 200);
        } else {
            flash('Tempcard created successfully')->success();
            return redirect()->to($redirect);
        }
    }

    private function _save($request, $Tempcard) {
        $data = $request->except(['_token','_method','from','to']);
        $Tempcard->fill($data);
        $Tempcard->from = $request->from;
        $Tempcard->to = $request->to;
        $Tempcard->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Tempcard $Tempcard) {
        $Model = $Tempcard;
        return view('admin.tempcard.view', compact('Model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Tempcard $Tempcard) {
        $data['Model'] = $Tempcard;
        $this->_append_form_variables($data);
        return view('admin.tempcard.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Tempcard $Tempcard) {
        $this->_validate($request, $Tempcard->id);

        $this->_save($request, $Tempcard);
        $redirect = route('tempcard.index', ['employee_type' => $Tempcard->employee->employeetype]);
        flash('Tempcard Updated successfully')->success();
        return redirect()->to($redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Tempcard $Tempcard) {
        $Tempcard->delete();
    }

    private function _validate($request, $id = null) {
        $this->validate($request, [
            'employee_id' => "required",
            'from' => "required",
            'to' => "required",
            'tempcard' => "required",
        ]);
    }
    
    public function active(Request $request, $id) {
        $model = Tempcard::find($id);
        $model->active = $request->active;
        $model->save();
        return response()->json(['success' => 'Success!', 'active' => $model->active], 200);
    }


}
