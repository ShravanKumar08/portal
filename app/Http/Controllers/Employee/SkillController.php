<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Models\Skill;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $skillModel = Skill::where('user_id', Auth::user()->id)->first();
            $data['skills'] = json_decode($skillModel->skills);
            $data ['id']    = $skillModel->id;
        } catch (Exception $e) {
            $data['skills'] = [];
        }

        return view('employee.skills.index', $data);
    }

    public function store(Request $request)
    {

        Skill::updateOrCreate(
            ['user_id' => Auth::user()->id],
            ['skills' => json_encode($request->all()['skills']) ]
        );

        $redirect = route('employee.skills.index');
        
        flash('Your skills updated successfully')->success();
        return redirect()->to($redirect);
    }
}
