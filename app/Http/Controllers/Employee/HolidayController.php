<?php

namespace App\Http\Controllers\Employee;

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
        $dataTable->role = "user";
        $data['years']= Holiday::getYears();
        if($request->year=='')
        $request->year=date("Y");
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('employee.holidays.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Holiday $holiday) {
        //
    }    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Holiday $holiday) {
        $Holiday = $holiday;

        return view('employee.holidays.view', compact('Holiday'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Holiday $holiday) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Holiday $holiday) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Holiday $holiday) {
        //
    }
}
