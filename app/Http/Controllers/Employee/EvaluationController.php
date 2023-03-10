<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\EvaluationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Assesment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\Setting;
use function flash;
use function redirect;
use function view;

class EvaluationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(EvaluationDataTable $dataTable) {

        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('employee.evaluation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    // public function create() {
    //     return view('employee.evaluation.create', compact('employees'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    // public function store(Request $request,Evaluation $Evaluation) {
       
    //         return redirect()->route('evaluation.index');
        
    // }

    private function _save($request, $Evaluation) {
        $data = $request->except(['_token']);
        $Evaluation->fill($data);
        $Evaluation->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Evaluation $evaluation) {
        $Model = $evaluation;
        return view('employee.evaluation.view', compact('Model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) 
    {

        $evaluation['id'] = Evaluation::find($id);
        // dd($evaluation);
        return view('employee.evaluation.edit',$evaluation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Evaluation $evaluation) {
        $this->_validate($request, $evaluation->id);
        $this->_save($request, $evaluation);
        
        flash('Evaluation Updated successfully')->success();
        return redirect()->route('employee.evaluation.index', ['scope' => $evaluation->assessment->employee_id != \Auth::user()->employee->id ? 'others' : 'self']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Evaluation $Evaluation) {
        $Evaluation->delete();
    }

    private function _validate($request, $id = null) {
        // $rules = [
        //     'name' => "required|unique:evaluations,name,$id,id,deleted_at,NULL",
        // ];
        // $this->validate($request, $rules);
    }
}
