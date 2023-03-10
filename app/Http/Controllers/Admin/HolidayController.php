<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\HolidayDataTable;
use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;

class HolidayController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(HolidayDataTable $dataTable,Request $request) {
        $dataTable->role = "admin";
        $data['years'] = ['2023'];

        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.holidays.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return view('admin.holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Holiday $holiday) {
        $this->_validate($request);
        
        $this->_save($request, $holiday);

        flash('Holiday created successfully')->success();
        return redirect('admin/holiday');
    }

    private function _save($request, $holiday) {
        $data = $request->except(['_token']);
        $holiday->fill($data);
        $holiday->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Holiday $holiday) {
        $Holiday = $holiday;

        return view('admin.holidays.view', compact('Holiday'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Holiday $holiday) {
        $Holiday = $holiday;
        return view('admin.holidays.edit', compact('Holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Holiday $holiday) {
        $this->_validate($request, $holiday->id);

        $this->_save($request, $holiday);

        flash('Holiday Updated successfully')->success();
        return redirect('admin/holiday');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Holiday $holiday) {
        $holiday->delete();
    }

    private function _validate($request, $id = null) {
        $rules = [
            'name' => 'required',
            'date' => "required|date|unique:holidays,date,$id,id,deleted_at,NULL",
        ];
        $this->validate($request, $rules);
    }

}
