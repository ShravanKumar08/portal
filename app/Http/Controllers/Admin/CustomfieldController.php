<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CustomfieldDataTable;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function flash;
use function redirect;
use function view;

class CustomfieldController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(CustomfieldDataTable $dataTable)
    {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.customfield.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $data = [];
        $this->_append_variables($data);
        return view('admin.customfield.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, CustomField $customfield)
    {
        $this->_validate($request);

//        $model = new CustomField();
        $this->_save($request, $customfield);
        if ($request->ajax()) {
            return response()->json(['success' => 'Success!'], 200);
        } else {
            flash('Customfields created successfully')->success();
            return redirect()->route('customfield.index');
        }
    }

    private function _save($request, $customfield)
    {
        $data = $request->except(['_token', 'roles']);
        $customfield->fill($data);
        if(@$data['padding']){
            $customfield->padding = @$data['padding'];
        }
        if(@$data['model_type']){
            $customfield->model_type = "App\Models\\".Str::title($data['model_type']);
        }
        $customfield->save();

        $customfield->roles()->sync($request->roles);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(CustomField $customfield)
    {
        $Model = $customfield;
        return view('admin.customfield.view', compact('Model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(CustomField $customfield)
    {
        $data['Model'] = $customfield;
        $this->_append_variables($data);
        return view('admin.customfield.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, CustomField $customfield)
    {
        $this->_validate($request, $customfield->id);

        $this->_save($request, $customfield);

        flash('Customfield Updated successfully')->success();
        return redirect()->route('customfield.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(CustomField $customfield)
    {
        $customfield->delete();
    }

    private function _validate($request, $id = null)
    {
        $rules = [];

        if(!$id){
            $request->request->add(['name' => $request->model_type."_".$request->field_name]);
            $rules['field_name'] = 'required';
        }

        $rules['name'] = "unique:custom_fields,name,{$id},id,deleted_at,NULL";
        $rules['label'] = 'required';

        $this->validate($request, $rules);
    }

    private function _append_variables(&$data)
    {
        $data['roles'] = Role::query()->latest('name')->pluck('name', 'id')->toArray();
    }
}
