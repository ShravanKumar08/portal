<?php

namespace App\Http\Controllers\Trainee;

use App\DataTables\EntryDataTable;
use App\Helpers\EntryHelper;
use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EntryController extends Controller
{
    protected $employee;
    protected $now;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(EntryDataTable $dataTable, Request $request)
    {
        $dataTable->role = "trainee";
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('trainee.entries.index',$data);
    }
    
   public function attendance($id)
    {
        $data['Model'] = Entryitem::find($id);
        return view('trainee.entries.index', $data);
    } 

//    public function start(Request $request)
//    {
//        $entryHelper = new EntryHelper(\Auth::user()->employee, Carbon::now());
//        $data = $entryHelper->start($request);
//
//        flash($data['message'])->{$data['status']}();
//        return redirect()->back();
//    }

    public function stop(Request $request)
    {
        $entryHelper = new EntryHelper(\Auth::user()->employee, Carbon::now());
        $data = $entryHelper->stop($request);

        flash($data['message'])->{$data['status']}();
        return redirect()->back();
    }
    
    public function timeronrequest(Request $request)
    {
        
        if(\Auth::user()->employee->timerStarted && !\Auth::user()->employee->traineeCanRequestTimer) {
            return redirect()->route('trainee.dashboard');
        }
        if($request->isMethod('POST')) {
            $model = Entry::firstOrCreate(['date' => date('Y-m-d'), 'employee_id' => \Auth::user()->employee->id]);

            $request->validate([
                'start' => 'required',
                'reason' => 'required',
            ]);

            $manualStartValue = Setting::fetch('MANUAL_REPORT_AUTOSTART');
            $model->start = $request->start;
            $model->reason = $request->reason;
            $model->inip = $request->ip();
            $model->status = $manualStartValue == 1 ? 'A' : 'P';
            $model->save();

            flash('Timer On Request Sent successfully.')->success();
            return redirect()->back();
        } else {    
            $model = Entry::where(['date' => date('Y-m-d'), 'employee_id' => \Auth::user()->employee->id])->first();
            return view('trainee.entries.timeronrequest', compact('model'));
        }
        
    }

    public function show($id)
    {
        $Model = Entry::find($id);
        return view('trainee.entries.view', compact('Model'));
    }
    
     public function getentryitems($id)
    {
        $entry = Entry::find($id);
        $items = $entry->getEntryItems();
        
        return view('layouts.partials.entryitems', compact('items','entry'));
    }
}
