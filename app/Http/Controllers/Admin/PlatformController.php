<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\DataTables\PlatformDataTable;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PlatformDataTable $dataTable,Request $request) {
        $dataTable->role = "admin";
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.platforms.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Platform $platform) {
        $this->_validate($request);

        $this->_save($request, $platform);

        flash('Platform created successfully')->success();
        return redirect('admin/platform');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Platform $platform)
    {
        $Platform = $platform;

        return view('admin.platforms.view', compact('Platform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function  edit(Platform $platform) {
        $Platform = $platform;

        return view('admin.platforms.edit', compact('Platform'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Platform $platform)
    {
        $this->_validate($request, $platform->id);

        $this->_save($request, $platform);

        flash('Platform Updated successfully')->success();
        return redirect('admin/platform');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Platform $platform) {
        $platform->delete();
    }

    private function _validate($request, $id = null, $uid = null) {
        $rules = [
            'name' => "required|unique:platforms,name,{$id},id,deleted_at,NULL",
        ];
        $this->validate($request, $rules);
    }

    private function _save($request, $platform) {
        $data = $request->except(['_token']);
        $platform->fill($data);
        $platform->save();
    }

}
