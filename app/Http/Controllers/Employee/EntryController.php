<?php

namespace App\Http\Controllers\Employee;

use App\DataTables\EntryDataTable;
use App\Helpers\EntryHelper;
use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Entryitem;
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
        $dataTable->role = "user";
        $data['request'] = $request;
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('employee.entries.index',$data);
    }
    
   public function attendance($id)
    {
        $data['Model'] = Entryitem::find($id);
        return view('employee.entries.index', $data);
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

    public function show($id)
    {
        $Model = Entry::find($id);
        return view('employee.entries.view', compact('Model'));
    }
    
    public function getentryitems($id)
    {
        $entry = Entry::find($id);
        $items = $entry->getEntryItems();
        
        return view('layouts.partials.entryitems', compact('items','entry'));
    }
}
