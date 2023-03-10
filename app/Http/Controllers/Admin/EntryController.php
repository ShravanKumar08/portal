<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EntryDataTable;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Entry;
use App\Models\Entryitem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;
use Illuminate\Support\Facades\Mail;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(EntryDataTable $dataTable, Request $request, Employee $employee, Entryitem $entryitem)
    {
        $query = Employee::oldest('name')->permanent();
        if($request->inactive_employee == 0){
            $query->active();
        }
        $data['employees_list'] = $employee->oldest('name')->active()->where('employeetype', $request->employeetype)->pluck("name", "id")->toArray();
        $data['request'] = $request;
        $data['entryitems'] = $entryitem;
        
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.entries.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->_append_form_variables($data);
        return view('admin.entries.create',$data);
    }
    
    protected function _append_form_variables(&$data) {
        $data['employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Entry $entry)
    {
        $this->_validate($request);

        $this->_save($request, $entry);
        $redirect = route('entry.index', ['employee_type' => $entry->employee->employeetype]);
        flash('Entry created successfully')->success();
        return redirect()->to($redirect);
    }
    
    private function _save($request, $entry) {
        $data = $request->except(['_token']);
        $entry->fill($data);
        $entry->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Entry $entry)
    {
        $Model = $entry;
        return view('admin.entries.view', compact('Model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Entry $entry)
    {
        $data['Model'] = $entry;
        $this->_append_form_variables($data);
        return view('admin.entries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Entry $entry)
    {
        $this->_validate($request);

        $this->_save($request, $entry);
        $redirect = route('entry.index', ['employee_type' => $entry->employee->employeetype]);
        flash('Entry Updated successfully')->success();
        return redirect()->to($redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Entry $entry)
    {
        $entry->delete();
    }
    
    private function _validate($request, $id = null) {
        $rules = [
            'employee_id' => 'required',
            'date' => 'required',
            'start' => 'required',
        ];
        $this->validate($request, $rules);
    }
    
    public function getentryitems($id)
    {
        $data['entry'] = Entry::find($id);
        $data['items'] = $data['entry']->getEntryItems();

        return view('layouts.partials.entryitems', $data);
    }
    
    private function _remarks_validate($request, $id = null) {
        $this->validate($request, [
            'end' => 'required',
        ]);
    }
    
    public function addremarks(Request $request) {
        $entry = Entry::find($request->id);
        if($entry->date != Carbon::now()->toDateString() && $request->status != 'D'){
                $this->_remarks_validate($request);
                $entry->end = $request->end;
            }
        $entry->remarks = $request->remarks;
        $entry->status = $request->status;
        $entry->end = $request->end;
        $entry->save();
        $entry->remarksMail();
        Mail::to($entry->employee->email)->queue(new \App\Mail\EntryNotification($entry));

        return response()->json(['success' => 'Success!', 'status' => $entry->status]);

    }
}
