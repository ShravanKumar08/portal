<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TeamDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function flash;
use function redirect;
use function view;
use App\Models\Employee;
use App\Models\Team;
use App\Models\TeamMember;

class TeamController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(TeamDataTable $dataTable) {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.teams.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Team $Team) {
        $data['Model'] = $Team;
        $this->_append_form_variables($data);

        return view('admin.teams.create', $data);
    }

    protected function _append_form_variables(&$data, $id = null) {
        $data['leads']      = Employee::oldest('name')->active()
            ->teamLead()
            ->pluck("name", "id")->toArray();

        if ($id) {
            $employees = Employee::oldest('name')->active()
                ->teamMembers();
            $data['teamMates']  = $employees->where(function ($q) use ($id) {
                    $q->whereHas('teamMerber', function ($teamQuery) use ($id) {
                        $teamQuery->where('team_id', @$id);
                    })->ordoesnthave('teamMerber', function ($teamQuery) {
                        return true;
                    });
                })->pluck("name", "id")->toArray();

            $data['checkedTeamMates'] = $employees->where(function ($q) use ($id) {
                $q->whereHas('teamMerber', function ($teamQuery) use ($id) {
                    $teamQuery->where('team_id', @$id);
                });
            })->pluck("id")->toArray();
        } else {
            $data['teamMates']  = Employee::oldest('name')->active()
                ->teamMembers()
                ->doesnthave('teamMerber')
                ->pluck("name", "id")->toArray();

            $data['checkedTeamMates'] = [];
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request,Team $team) {
        $this->_validate($request);
        $this->_save($request, $team);
        $redirect = route('teams.index');
        flash('Team created successfully')->success();

        return redirect()->to($redirect);
    }

    private function _save($request, $team) {

        $team = $this->_save_team($request, $team);
        $this->_save_team_members($request, $team);
        
    }

    private function _save_team($request, $team)
    {
        $team->fill($request->all());
        $team->save();

        return $team;
    }

    private function _save_team_members($request, $team)
    {
        TeamMember::where('team_id', $team->id)->delete();

        foreach($request->teammate_id as $id) {
            $teamMember = new TeamMember();
            $teamMember->team_id = $team->id;
            $teamMember->teammate_id = $id;
            $teamMember->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Team $team) {
        $data['Model'] = $team;
        $data['checkedTeamMates'] = Employee::oldest('name')->active()
            ->teamMembers()->where(function ($q) use ($team) {
            $q->whereHas('teamMerber', function ($teamQuery) use ($team) {
                $teamQuery->where('team_id', @$team->id);
            });
        })->pluck("name")->toArray();

        return view('admin.teams.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Team $team) {
        $data['Model'] = $team;
        $this->_append_form_variables($data, $team->id);
        return view('admin.teams.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Team $team) {
        $this->_validate($request);
        $this->_save($request, $team);
        $redirect = route('teams.index');
        flash('Team Updated successfully')->success();

        return redirect()->to($redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Team $team) {
        $team->teamMembers()->delete();
        $team->delete();
    }

    private function _validate($request, $id = null) {
        $this->validate($request, [
            'name'          => "required",
            'lead_id'       => "required",
            'teammate_id'   => "required|array|min:1"
        ]);
    }
}
