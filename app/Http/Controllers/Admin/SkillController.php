<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SkillDataTable;
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
    public function index(SkillDataTable $dataTable) {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.skills.index');
    }
}
