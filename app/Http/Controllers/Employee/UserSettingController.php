<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\UserSettingDataTable;
use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;

class UserSettingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(UserSettingDataTable $dataTable)
    {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('employee.usersettings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(UserSettings $usersetting)
    {
//        $data['Model'] = $usersetting;
//        return view('employee.usersettings.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, UserSettings $usersetting)
    {
//        $this->_validate_settings($request);
//        $this->_save($request, $usersetting);
//
//        flash('Setting created successfully')->success();
//        return redirect()->route('employee.usersettings.index');
    }

    private function _save($request, $model)
    {
        abort_if($model->user_id != \Auth::user()->id, '403', 'Access denied');

        if (is_array($request->value)) {
            $model->value = json_encode($request->value);
        }else{
            $model->value = $request->value;
        }

        $model->save();
    }

    public function _validate_settings(Request $request)
    {
        $rules = [];
        if (@$request->name == UserSettings::GITHUB_CREDENTIALS) {
            $rules = ['value.username' => 'required', 'value.personalaccesstoken' => 'required'];
        }
        $customMessages = [
            'value.username.required' => 'The username field is required.',
            'value.personalaccesstoken.required' => 'The personal access token field is required.'
        ];
        if ($rules) {
            $this->validate($request, $rules, $customMessages);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(UserSettings $usersetting)
    {
        $Model = $usersetting;
        return view('employee.usersettings.view', compact('Model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(UserSettings $usersetting)
    {
        $data['Model'] = $usersetting;
        abort_if($usersetting->user_id != \Auth::user()->id, '403', 'Access denied');

        $data['value'] = json_decode($data['Model']->value);
        return view('employee.usersettings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, UserSettings $usersetting)
    {
        $this->_validate_settings($request);
        $this->_save($request, $usersetting);

        flash('Setting Updated successfully')->success();
        return redirect()->route('employee.usersettings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(UserSettings $usersetting)
    {
        $usersetting->delete();
    }

}
