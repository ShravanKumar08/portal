<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\AssesmentDataTable;
use App\Http\Controllers\Controller;
use App\Models\Assesment;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Employee;
use App\Models\Setting;
use PDF;
use function flash;
use function redirect;
use function view;

class AssesmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(AssesmentDataTable $dataTable)
    {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.assesment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $evaluation = Setting::whereIn('name', ['SELF_EVALUATION_FORM', 'REPORTHEAD_EVALUATION_FORM'])->get();
        foreach (@$evaluation as $evaluate) {
            $contents[$evaluate->name] = [
                'content' => $evaluate->value,
            ];
        }
        $employees = Employee::oldest('name')->active()->get()->pluck("name", "id")->toArray();
        return view('admin.assesment.create', compact('employees', 'contents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, Assesment $assesment)
    {
        // $this->_validate($request);
        $this->_save($request, $assesment);
        flash('Assesment created successfully')->success();
        return redirect()->route('assesment.index');
    }

    private function _save($request, $assesment)
    {   
        $data = $request->only(['from', 'to', 'employee_id']);
        $assesment->fill($data);
        $assesment->save();
        
        //Save self evaluation
        $evaluation = Evaluation::firstOrNew([
            'assessment_id' => $assesment->id,
            'evaluator_id' => $request->employee_id,
        ]);
        $evaluation->evaluation = $this->_replace_eval_content($request, $evaluation, $request->self);
        $evaluation->save();

        //Save report Head evaluation
        $teamleader = $request->report_head;
        foreach ($teamleader as $team) {
            $evaluation = Evaluation::firstOrNew([
                'assessment_id' => $assesment->id,
                'evaluator_id' => $team,
            ]);
            $evaluation->evaluation = $this->_replace_eval_content($request, $evaluation, $request->teamleader);
            $evaluation->save();
        }
    }

    private function _replace_eval_content($request, $evaluation, $content){
        $content = Setting::strReplaceEmployeeContent($content, $evaluation->employee);
        $reportheads = Employee::whereIn('id',$request->report_head)->get()->implode('name', ', ');
        $selfname = Employee::find($request->employee_id)->name;
        $content = str_replace(["{assessment.from}", "{assessment.to}", "{reporthead.name}", "{self.name}"], [ $evaluation->assessment->from, $evaluation->assessment->to, $reportheads, $selfname], $content);

        return $content;
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Assesment $assesment)
    {
        $evaluations = $assesment->evaluations()->join('employees', 'evaluator_id', '=', 'employees.id')->oldest('employees.name')->get();
        return view('admin.assesment.view', compact('evaluations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Assesment $assesment)
    {
        $Model = $assesment;
        return view('admin.assesment.edit', compact('Model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Assesment $assesment)
    {
        $this->_validate($request, $assesment->id);
        $this->_save($request, $assesment);
        flash('Assesment Updated successfully')->success();
        return redirect()->route('assesment.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Assesment $assesment)
    {
        $assesment->delete();
    }

    private function _validate($request, $id = null)
    {
        $rules = [
            'name' => "required|unique:assesments,name,$id,id,deleted_at,NULL",
        ];
        $this->validate($request, $rules);
    }

    public function active(Request $request, $id)
    {
        $model = Assesment::find($id);
        $model->active = $request->active;
        $model->save();
        return response()->json(['success' => 'Success!', 'active' => $model->active], 200);
    }

    public function reupdate(Request $request)
    {
        $evaluation = Evaluation::where('evaluator_id',$request->id)->first();
        $evaluation->evaluation = $request->teamleader;
        $evaluation->save();
        flash('Assesment updated successfully')->success();
        return redirect()->back();
    }

    public function evaluation_download(Request $request,$id)
    {
        $data = ['evaluation' => $request->evaluation];
        return PDF::loadView('admin.assesment.pdf', $data)->download('evaluation.pdf');
    }
}
