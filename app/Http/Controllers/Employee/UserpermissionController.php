<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\UserPermissionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckCreatePermission;
use App\Http\Middleware\CheckEditPermission;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Userpermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;

class UserpermissionController extends Controller {

    public function __construct()
    {
        $this->middleware(CheckCreatePermission::class)->only(['create', 'store']);
        $this->middleware(CheckEditPermission::class)->only(['edit', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(UserPermissionDataTable $dataTable, Request $request, Userpermission $Userpermission) {
        $dataTable->role = "employee";
        $data['statuses'] = $Userpermission::$status;
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('employee.userpermissions.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Userpermission $userPermission) {
        $data['Model'] = $userPermission;
        $this->_append_form_variables($data);
        return view('employee.userpermissions.create',$data);
    }
    
    protected function _append_form_variables(&$data) {
        $data['status'] = Userpermission::$status;
        $data['employees'] = Employee::pluck("name","id")->toArray();
        $data['Holidays'] = Holiday::pluck("date")->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Userpermission $Userpermission) {
        $this->_append_hidden_values($request);
        $this->validate($request, $Userpermission::getRules($request));
        $Userpermission->saveForm($request);

        flash('Userpermission created successfully')->success();
        return redirect()->route('employee.userpermission.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Userpermission $Userpermission,Employee $employee) {
        $data['Model'] = $Userpermission;
        $data['Employee'] = $employee->where('id',$data['Model']->employee_id)->first();
        return view('employee.userpermissions.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id) {
        $data['Model'] = $request->Model;
        $this->_append_form_variables($data);
        return view('employee.userpermissions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Userpermission $Userpermission) {
        $this->_append_hidden_values($request);
        $this->validate($request, $Userpermission::getRules($request, $Userpermission->id));

        $Userpermission = $request->Model;
        $Userpermission->saveForm($request);

        flash('Userpermission Updated successfully')->success();
        return redirect()->route('employee.userpermission.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
//        Userpermission::find($id)->delete();
    }

    private function _append_hidden_values($request)
    {
        $request->request->add(['employee_id' => \Auth::user()->employee->id, 'status' => "P"]);
    }
}
