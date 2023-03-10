<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\InterviewPrescreeningDataTable;
use App\Helpers\CustomfieldHelper;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\CustomFieldValue;
use App\Models\CustomField;
use App\Models\InterviewCall;
use App\Models\Employee;
use App\Models\InterviewCandidate;
use App\Models\InterviewPrescreening;
use App\Models\InterviewRemark;
use App\Models\InterviewRound;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use function flash;
use function redirect;
use function view;

class InterviewPrescreeningController extends Controller {

    public function index(InterviewPrescreeningDataTable $dataTable) {

        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.interviewprescreening.index');
    }

    protected function _append_form_variables(&$data) {
        $data['custom_fields'] = CustomfieldHelper::getCustomfieldsByModule(InterviewPrescreening::class);
    }

    public function create() {

        $this->_append_form_variables($data);
        return view('admin.interviewprescreening.create', $data);
    }

    public function store(Request $request) {
        $this->_validate($request);

        $data = $request->except(['_token']);
        $prescreen = new InterviewPrescreening;
        $prescreen->fill($data);
        $prescreen->save();
        $prescreen->saveStatus($request);

        flash('Created successfully')->success();
        return redirect()->route('interviewprescreening.index');
    }

    public function show($id) {
        $Model =  InterviewPrescreening::find($id);

        $field = CustomField::where('name', 'interviewprescreening_status')->first();

        $custom_val = CustomFieldValue::where('model_id', $id)->where('custom_field_id', $field->id)->first();

        return view('admin.interviewprescreening.view', compact('Model','custom_val'));
    }

    public function edit($id) {

        $data['Model'] = InterviewPrescreening::findPrescreeningById($id);
        $this->_append_form_variables($data);

        return view('admin.interviewprescreening.edit', $data);
    }

    public function update(Request $request, $id) {
        $InterviewPrescreening =  InterviewPrescreening::find($id);

        $this->_validate($request, $InterviewPrescreening->id);
        $data = $request->except(['_token']);

        $InterviewPrescreening->fill($data);
        $InterviewPrescreening->save();
        $InterviewPrescreening->saveStatus($request);
       
        flash('Updated successfully')->success();
        return redirect()->route('interviewprescreening.index');
    }

    public function destroy($id) {
        InterviewPrescreening::find($id)->delete();
    }

    private function _validate($request, $id = null, $uid = null) {
        $rules = [
            'email' => "required|email",
            'phone' => "required",
            'location'=> "required",
            'name' => [
                'required',
                    function ($attribute, $value, $fail) use($request, $id) {
                    if(!$id){
                        $data = InterviewPrescreening::where('name' , $request->name)->where('email', $request->email)->where('phone', $request->phone)->first();
                        if ($data) {
                            return $fail('The data is already exists');
                        }
                    }
                   
                },
            ],
        ];
        
        CustomfieldHelper::appendCustomModuleRules( InterviewPrescreening::class, $rules);
        
        $this->validate($request, $rules);
    }
}
