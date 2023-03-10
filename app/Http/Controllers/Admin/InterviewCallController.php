<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\InterviewCallDataTable;
use App\Helpers\CustomfieldHelper;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\InterviewCall;
use App\Models\Employee;
use App\Models\InterviewCandidate;
use App\Models\CustomFieldValue;
use App\Models\Interviewstatus;
use App\Models\InterviewRemark;
use App\Models\InterviewRound;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use function flash;
use function redirect;
use function view;
use Carbon\Carbon;

class InterviewCallController extends Controller
{


    public function index(InterviewCallDataTable $dataTable)
    {

        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.interviewstatus.index');
    }

    protected function _append_form_variables(&$data)
    {
        $data['custom_fields'] = CustomfieldHelper::getCustomfieldsByModule(InterviewCall::class);
        $data['round_custom_fields'] = CustomfieldHelper::getCustomfieldsByModule(InterviewRound::class);

    }

    public function create(Designation $Designation)
    {
        $designationlist = $Designation->oldest('name')->get()->pluck('name', 'id');
        $Employees = Employee::oldest('name')->active()->where('employeetype', 'P')->pluck("name", "id")->toArray();
        $rounds = InterviewRound::$rounds;
        // $statuses = InterviewRound::$statuses;
        $this->_append_form_variables($data);
        return view('admin.interviewcall.create', compact('designationlist', 'rounds', 'Employees'), $data);
    }

    public function store(Request $request, InterviewCall $InterviewCall)
    {
        $this->_validate($request);
        $this->_save($request, $InterviewCall);

        $this->_saveRounds($request, $InterviewCall);

        $remarks = new InterviewRemark;
        $remarkdata['created_by'] = \Auth::user()->id;
        $remarkdata['interview_call_id'] = $InterviewCall->id;
        $remarkdata['remarks'] = 'Call started';
        $remarks->fill($remarkdata);
        $remarks->save();

        flash('Candidate details Uploaded successfully')->success();
        return redirect()->route('interviewcall.index');
    }

    public function show($id)
    {
        $model = InterviewCall::find($id);
        $candidate = $model->candidate;
        $status = Interviewstatus::get()->pluck('name', 'id');
        $remarks = InterviewRemark::where('interview_call_id', $model->id)->orderBy('created_at', 'DESC')->get();
        $interviewcalls = InterviewCall::where('id', $model->id)->get();
        $designation = $candidate->designation;
        foreach ($remarks as $key => $value) {
            $employee[$key] = $remarks[$key]->user;
            // $remmarkStatus[$key] = $remarks[$key]->status;
        }
        foreach ($interviewcalls as $key => $values) {
            $callStatus[$key] = $interviewcalls[$key]->status;
        }
        return view('admin.interviewcall.view', compact('model', 'candidate', 'interviewcalls', 'status', 'remarks', 'employee', 'callStatus', 'designation'));
    }

    public function showInterviewCall($id)
    {
        $model = InterviewCall::find($id);
        $candidate = $model->candidate;
        $designation = $candidate->designation;

        return view('admin.interviewcall.show', compact('model', 'candidate', 'designation'));
    }

    public function edit($id)
    {
        $data['CandidateCall'] = InterviewCall::findCandidateById($id);
        $data['candidate'] = $data['CandidateCall']->candidate;
        $data['candidateRound'] = $data['CandidateCall']->interview_round;
        $Employees = Employee::oldest('name')->active()->where('employeetype', 'P')->pluck("name", "id")->toArray();
        $rounds = InterviewRound::$rounds;
        //  $model = $interviewcall;
        //  $candidate = $model->candidate;
        $designationlist = Designation::get()->pluck('name', 'id');
        $this->_append_form_variables($data);

        foreach ($data['candidateRound'] as $k => $round) {
            if ($cfval = $round->cfval) {
                foreach ($cfval as $column => $value) {
                    $data['CandidateCall']->roundInf[$k + 1]['customfield'][$column] = $value;
                }
            }
        }


        return view('admin.interviewcall.edit', compact('designationlist', 'rounds', 'Employees'), $data);
    }

    public function update(Request $request, $id)
    {
        $InterviewCall = InterviewCall::find($id);

        $this->_validate($request);
        $this->_save($request, $InterviewCall);
        $this->_saveRounds($request, $InterviewCall);
        flash('Candidate Details Updated successfully')->success();
        return redirect()->route('interviewcall.index');
    }

    public function destroy($id)
    {
        InterviewCall::find($id)->delete();
    }

    private function _validate($request, $id = null)
    {
        $rules = [
            'candidate.email' => "required|email",
            'candidate.mobile' => "required",
            'candidate.permanent_location' => "required",
            'candidate.gender' => "required",
            'candidate.martial_status' => "required",
            'candidate.designation_id' => "required",
            'experience' => "required",
            'present_location' => "required",
            'present_company' => "required",
            'change_reason' => "required",
        ];
        CustomfieldHelper::appendCustomModuleRules(InterviewCall::class, $rules);

        $this->validate($request, $rules);
    }

    private function _save($request, $InterviewCall)
    {
        $data = $request->candidate;
        if ($request->file('resume')) {
            $file = $request->file('resume');
            $filename = 'candidate-Document-' . time() . '.' . $file->getClientOriginalExtension();
            $data['resume'] = $file->storeAs('candidate', $filename, 'public');
        }

        $candidate = InterviewCandidate::updateOrCreate(
            [
                'email' => $request->candidate['email']
            ],
            $data
        );

        $InterviewCall->fill($request->except(['candidate', '_token', '_method']));
        $InterviewCall->interview_candidate_id = $candidate->id;
        $InterviewCall->save();
        $InterviewCall->saveCandidateProfile($request);

    }

    private function _saveRounds($request, $InterviewCall)
    {
        foreach ($request->roundInf as $roundinf) {
            $remarkdata = [];

            $interviewRound = InterviewRound::firstOrNew([
                'round' => $roundinf['round'],
                'interviewcall_id' => $InterviewCall->id,
            ]);

            $interviewRound->fill($roundinf);

            $dirty = $interviewRound->getDirty();
            $originals = $interviewRound->getOriginal();

            $interviewRound->save();

            $savedRoundStatus = @$interviewRound->cfval->interviewround_status;
            $currentRoundStatus = $roundinf['customfield']['interviewround_status'];

            $isNewStatus = !isset($savedRoundStatus);
            
            $interviewRoundNames = InterviewRound::$rounds;
            
            $roundName = $interviewRoundNames[$interviewRound->round];

            if ($currentRoundStatus) {
                if ($isNewStatus) {
                    $remarkdata[] = "Status is " . $currentRoundStatus . " in " . "  $roundName";
                } else {
                    if ($savedRoundStatus != $currentRoundStatus) {
                        $remarkdata[] = "Status changed from " . $savedRoundStatus . " to " . $currentRoundStatus . " in " . "$roundName";
                    }
                }
            }

            $interviewRound->saveStatus($roundinf['customfield']);

            if ($interviewRound->round == 1) {
                if ($interviewRound->datetime && !$roundinf['id']) {
                    $remarkdata[] = "Scheduled at " . date('Y-m-d h:i:s A', strtotime($interviewRound->datetime)) . " in " . "$roundName";
                } else if (isset($dirty['datetime'])) {
                    $remarkdata[] = "Schedule date changed  from " . date('Y-m-d h:i:s A', strtotime(@$originals['datetime'])) . " to " . date('Y-m-d h:i:s A', strtotime($interviewRound->datetime)) . " in " . "$roundName";
                }

            }

            if ($interviewRound->round == 3) {
                if ($interviewRound->employee_id && !$roundinf['id']) {
                    $remarkdata[] = $roundName . " was taken by " . $interviewRound->employee->name;
                } else if (isset($dirty['employee_id']) && isset($originals['employee_id'])) {
                    $emp = Employee::find($originals['employee_id'])->name;
                    $remarkdata[] = $roundName. " was handover from  " . $emp . " to " . $interviewRound->employee->name;
                }
            }

            if (isset($remarkdata)) {
                foreach ($remarkdata as $remarkdatum) {
                    $remarks = new InterviewRemark;
                    $remarks->created_by = Auth::user()->id;
                    $remarks->interview_call_id = $interviewRound->interviewcall_id;
                    $remarks->remarks = $remarkdatum;
                    $remarks->save();
                }
            }
        }
    }

    public function active(Request $request, $id)
    {
        $model = Designation::find($id);
        $model->active = $request->active;
        $model->save();
        return response()->json(['success' => 'Success!', 'active' => $model->active], 200);
    }

    public function status(Request $request, $id)
    {
        $user = Auth::user();
        $data = $request->except(['_token', '_method']);
        $data['schedule_date'] = date('Y-m-d H:i:s', strtotime($data['schedule_date']));
        $interviewcall = InterviewCall::find($id);
        $interviewcall->fill($data);

        $dirty = $interviewcall->getDirty();
        $originals = $interviewcall->getOriginal();

        $interviewcall->save();

        if (isset($dirty['interview_status_id'])) {
            $remarks = new InterviewRemark;
            $remarkdata['created_by'] = $user->id;
            $remarkdata['interview_call_id'] = $interviewcall->id;
            $status_from = Interviewstatus::find($originals['interview_status_id'])->name;
            $remarkdata['remarks'] = "Status changed from " . $status_from . " to " . $interviewcall->status->name;
            $remarks->fill($remarkdata);
            $remarks->save();
        }

        if (isset($dirty['schedule_date'])) {
            $remarks = new InterviewRemark;
            $remarkdata['created_by'] = $user->id;
            $remarkdata['interview_call_id'] = $interviewcall->id;
            if ($originals['schedule_date']) {
                $remarkdata['remarks'] = "Schedule date changed from " . date('Y-m-d h:i A', strtotime($originals['schedule_date'])) . " to " . date('Y-m-d h:i A', strtotime($interviewcall->schedule_date));
            } else {
                $remarkdata['remarks'] = "Scheduled at " . date('Y-m-d h:i A', strtotime($interviewcall->schedule_date));
            }
            $remarks->fill($remarkdata);
            $remarks->save();
        }

        if (!empty($request->remarks)) {
            $remarkdata = $request->except(['_token', '_method', 'interview_status_id']);
            $remarks = new InterviewRemark;
            $remarkdata['created_by'] = $user->id;
            $remarks->fill($remarkdata);
            $remarks->save();

            flash('Remarks  updated  sucessfully')->success();
            return redirect()->route('interviewcall.show', $id);
        } else {
            flash('Status updated  sucessfully')->success();
            return redirect()->route('interviewcall.show', $id);
        }
    }

    public function getcandidates(request $request)
    {
        $query = InterviewCandidate::query();

        if ($name = $request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($email = $request->email) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }
        if ($mobile = $request->mobile) {
            $query->where('mobile', 'LIKE', '%' . $request->mobile . '%');
        }
        $candidates = $query->get();
        $count = $candidates->count();
        $list = '';
        if ($count != 0) {
            foreach ($candidates as $key => $value) {
                $img = asset('/assets/images/success.png');
                $list .= '<div class="box-thumb" id="candidate" data-id=' . $value->id . '  data-name=' . $value->name . ' data-email=' . $value->email . '  data-mobile =' . $value->mobile . ' data-martial_status =' . $value->martial_status . ' data-permanent_location=' . $value->permanent_location . ' data-gender=' . $value->gender . ' data-designation=' . $value->designation_id . ' class=box-thumb __web-inspector-hide-shortcut_"><div data-name =id =' . $value->name . '   class=""> <h6>' . $value->name . '</h6><p>' . $value->email . '</p></div><img src=' . $img . '></div>';
            }
            $status = 1;
        } else {
            $list = "<div class='box-thumb __web-inspector-hide-shortcut__'><div class=''><h6>Record Not Found</h6>";
            $status = 0;
        }
        return response()->json(['success' => 'Success!', 'list' => $list, 'status' => $status, 'candidates' => '$candidates'], 200);

    }
}
