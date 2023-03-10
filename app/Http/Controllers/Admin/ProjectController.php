<?php

namespace App\Http\Controllers\Admin;
use App\DataTables\MergeprojectDataTable;

use App\DataTables\ProjectDataTable;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;
use Illuminate\Support\Facades\DB;
use App\Models\ReportItem;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(ProjectDataTable $dataTable) {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.projects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, Project $project) {
        $this->_validate($request);
        $this->_save($request, $project);

        flash('Project created successfully')->success();
        return redirect()->route('project.index');
    }

    private function _save($request, $model) {
        $data = $request->except(['_token']);
        $model->fill($data);
        $model->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Project $project) {
        $Model = $project;
        return view('admin.projects.view', compact('Model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Project $project) {
        $data['Model'] = $project;
        return view('admin.projects.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Project $project) {
        $this->_validate($request, $project->id);
        $this->_save($request, $project);

        flash('Project Updated successfully')->success();
        return redirect()->route('project.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Project $project) {
        $project->delete();
    }

    private function _validate($request, $id = null) {
        $this->validate($request, [
            'name' => "required|unique:projects,name,$id,id,deleted_at,NULL",
        ]);
    }
    
    public function active(Request $request, $id) {
        $model = Project::find($id);
        $model->active = $request->active;
        $model->save();
        return response()->json(['success' => 'Success!', 'active' => $model->active], 200);
    }

    public function mergeProject(Request $request)
    {
        $data['request'] = $request;
        if ($request->isMethod('post')) {
            $merge_ids = array_diff($request->merge_project_ids, [$request->primary_project_id]);

            ReportItem::whereIn('project_id', $merge_ids)->update(['project_id' => $request->primary_project_id]);
            Project::whereIn('id', $merge_ids)->delete();

            flash('Project merged successfully')->success();
            return redirect()->back();
        }

        $data['max'] = Project::query()->selectRaw('max(char_length(name)) as max')->first()->max;

         if ($request->ajax()) {
            $from = 1; $to = 6;

            if($request->filter){
                list($from, $to) = explode(';', $request->filter);
            }
       
            $results = DB::select(DB::raw("SELECT name, count(*) as cnt, group_concat(id SEPARATOR ';') as merge_ids, group_concat(name SEPARATOR ';') as merge_name
            from projects
            where deleted_at is null
            group by SUBSTRING(SOUNDEX(name), $from, $to)
            having cnt > 1"));

            return DataTables::collection($results)->addColumn('action', function($model){
                return '<a href="javascript:void(0)" data-id="'. $model->name .'" data-toggle="modal" data-merge_ids="'. $model->merge_ids .'" data-name="'. $model->name .'" data-merge_name="'. $model->merge_name .'" 
                data-target="#DatatableViewModal"><button class="btn btn-primary" title="Merge">Merge</button></a>';
            })->make(true);
        }

        return view('admin.projects.partials.mergeproject', $data);
    }
    
    public function showAudits(Request $request) {
        $project = Project::find($request->project_id);
        $datas = $project->audits()->latest()->get();
        return view("layouts.partials.audits", compact('datas'));
    }
   
}
