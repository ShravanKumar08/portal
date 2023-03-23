<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Models\IDP;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IDPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data['model'] = Auth::user()->employee->idp;

        return view('employee.idps.index', $data);
    }

    public function store(Request $request)
    {
        $idp = Auth::user()->employee->idp;

        if(!$idp) {

            $idp = new IDP();
            $idp->employee_id = Auth::user()->employee->id;
            $idp->save();
        }

        $idp->fill($request->all());
        $idp->save();

        $data['model'] = $idp;

        $redirect = route('employee.idps.index');
        flash('Individual Development Plan updated successfully')->success();

        return redirect()->to($redirect);
    }
}
