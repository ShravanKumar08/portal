<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DesignationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function flash;
use function redirect;
use function view;

class DesignationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(DesignationDataTable $dataTable) {
    
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.designation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return view('admin.designation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request,Designation $designation) {
        $this->_validate($request);
        
        $this->_save($request, $designation);
        if ($request->ajax()) {
            return response()->json(['success' => 'Success!'], 200);
        } else {
            flash('Designation created successfully')->success();
            return redirect()->route('designation.index');
        }
    }

    private function _save($request, $designation) {
        $data = $request->except(['_token']);
        $designation->fill($data);
        $designation->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Designation $designation) {
        $Model = $designation;
        return view('admin.designation.view', compact('Model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Designation $designation) {
        $Model = $designation;
        return view('admin.designation.edit', compact('Model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Designation $designation) {
        $this->_validate($request, $designation->id);

        $this->_save($request, $designation);

        flash('Designation Updated successfully')->success();
        return redirect()->route('designation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Designation $designation) {
        $designation->delete();
    }

    private function _validate($request, $id = null) {
        $rules = [
            'name' => "required|unique:designations,name,$id,id,deleted_at,NULL",
        ];
        $this->validate($request, $rules);
    }
    
    public function active(Request $request, $id) {
        $model = Designation::find($id);
        $model->active = $request->active;
        $model->save();
        return response()->json(['success' => 'Success!', 'active' => $model->active], 200);
    }

}
