<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\DataTables\GradeDataTable;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GradeDataTable $dataTable,Request $request) {
        $dataTable->role = "admin";
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.grades.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.grades.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Grade $grade) {
        $this->_validate($request);

        $this->_save($request, $grade);

        flash('Grade created successfully')->success();
        return redirect('admin/grade/create');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        $Grade = $grade;

        return view('admin.grades.view', compact('Grade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function  edit(Grade $grade) {
        $Grade = $grade;

        return view('admin.grades.edit', compact('Grade'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grade $grade)
    {
        $this->_validate($request, $grade->id);

        $this->_save($request, $grade);

        flash('Grade Updated successfully')->success();
        return redirect('admin/grade');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade) {
        $grade->delete();
    }

    private function _validate($request, $id = null, $uid = null) {
        $rules = [
            'name' => "required|unique:grades,name,{$id},id,deleted_at,NULL",
            'level' => 'required|integer|between:0,10',
        ];
        $this->validate($request, $rules);
    }

    private function _save($request, $grade) {
        $data = $request->except(['_token']);
        $grade->fill($data);
        $grade->save();
    }

}
