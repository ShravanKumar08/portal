<?php

use Illuminate\Database\Seeder;
use Carbon\CarbonPeriod;

class ImportSeeder extends Seeder
{
    protected $db;

    protected $projects = [];

    protected $technologies = [];

    protected $user_employees = [];

    protected $leaves = [];

    protected $compensations = [];


    public function __construct()
    {
        $this->db = DB::connection('import_database');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->import_holidays();
        $this->import_projects();
        $this->import_technologies();
        $this->import_users();
        $this->import_late_entries();
        $this->import_permissions();
        $this->import_leaves();
        $this->import_compensations();
        $this->import_entries();
        $this->import_reports();
    }

    protected function import_holidays()
    {
        $records = $this->db->table('holidays')->get();

        foreach ($records as $record) {
            try {
                \App\Models\Holiday::firstOrCreate([
                    'name' => title_case($record->name),
                    'date' => $record->date,
                ]);
            } catch (\Exception $e) {
                $this->exception($e);
            }
        }
    }

    protected function import_technologies()
    {
        $records = $this->db->table('categories')->get();

        foreach ($records as $record) {
            try {
                $name = $record->category;

                $technology = \App\Models\Technology::firstOrCreate([
                    'name' => title_case($name)
                ]);

                if (str_contains(strtolower($name), ['lunch', 'tea break'])) {
                    $technology->exclude = 1;
                    $technology->save();
                }

                $this->technologies[$record->id] = $technology;

            } catch (\Exception $e) {
                $this->exception($e);
            }
        }
    }

    protected function import_projects()
    {
        $records = $this->db->table('projects')->get();

        foreach ($records as $record) {
            $name = substr($record->projectname, 0, 99);

            try {
                $project = \App\Models\Project::firstOrCreate([
                    'name' => title_case($name)
                ]);
                $this->projects[$record->id] = $project;
            } catch (\Exception $e) {
                $this->exception($e);
            }
        }
    }

    protected function import_users()
    {
        $records = $this->db->table('users')->get();
        $officetimings = \App\Models\Officetiming::where("name", "Default Timings")->first();

        foreach ($records as $record) {
            if ($record->role == 'user') {
                try {
                    $user = \App\Models\User::firstOrNew(['email' => $record->email]);

                    if (!$user->password) {
                        $user->password = bcrypt(\App\Models\User::DEFAULT_PASSWORD);
                    }

                    $user->name = $record->employee_name;
                    $user->active = $record->active;
                    $user->save();

                    $designation = \App\Models\Designation::firstOrCreate(['name' => $record->designation]);
                    $employee = $user->employee ?: new App\Models\Employee();

                    $employee->name = $record->employee_name;
                    $employee->user_id = $user->id;
                    $employee->employeetype = $record->employee_type;
                    $employee->gender = $record->sex;
                    $employee->dob = $record->date_of_birth;
                    $employee->designation_id = $designation->id;
                    $employee->officetiming_id = $officetimings->id;
                    $casual_count_per_year[Carbon\Carbon::now()->year] = $record->casual_leave;
                    $employee->casual_count_per_year = $casual_count_per_year;

                    $employee->save();
                    if ($employee->employeetype == 'P') {
                        $assign_role = 'employee';
                        $remove_role = 'trainee';
                    } else if ($employee->employeetype == 'T') {
                        $assign_role = 'trainee';
                        $remove_role = 'employee';
                    }

                    if (@$assign_role && !$user->hasRole($assign_role)) {
                        $user->assignRole($assign_role);
                    }
                    if (@$remove_role && $user->hasRole($remove_role)) {
                        $user->removeRole($remove_role);
                    }

                    $customfields['employee_fathername'] = $record->fathername;
                    $customfields['employee_mothername'] = $record->mothername;
                    $customfields['employee_spousename'] = $record->spousename;
                    $customfields['employee_skype'] = $record->skype;
                    $customfields['employee_passport'] = $record->passport;
                    $customfields['employee_pannumber'] = $record->pancard;
                    $customfields['employee_aadhaarcardnumber'] = $record->aadharid;
                    $customfields['employee_emergencycontactnumber'] = $record->emergency_contact;
                    $customfields['employee_joinedon'] = $record->joined_on;
                    $customfields['employee_address'] = $record->address;
                    $customfields['employee_city'] = $this->db->table('cities')->find($record->city_id)->city;
                    $customfields['employee_state'] = $this->db->table('states')->find($record->state_id)->state;
                    $customfields['employee_country'] = $this->db->table('countries')->find($record->country_id)->country;
                    $customfields['employee_phonenumber'] = $record->phone;
                    $customfields['employee_deviceuserid'] = $record->employee_id;

                    $employee->save_customdata($customfields, false);

                    $this->user_employees[$record->id] = $employee;
                } catch (\Exception $e) {
                    $this->exception($e);
                }
            }
        }
    }

    protected function import_reports()
    {
        $user_record_groups = $this->db->table('daily_statuses')->get()->groupBy(['user_id']);

        foreach ($user_record_groups as $user_record_group) {
            $user_records = $user_record_group->groupBy('date')->sortBy('start_time');

            foreach ($user_records as $date => $records) {
                $first = $records->first();
                $last = $records->last();

                if ($employee = @$this->user_employees[$first->user_id]) {
                    try {
                        $report = \App\Models\Report::firstOrCreate([
                            'employee_id' => $employee->id,
                            'date' => $date,
                            'start' => \Carbon\Carbon::parse($first->start_time)->toTimeString(),
                            'end' => \Carbon\Carbon::parse($last->end_time)->toTimeString(),
                            'status' => 'S',
                        ]);

                        foreach ($records as $record) {

                            $item = \App\Models\ReportItem::firstOrNew([
                                'report_id' => $report->id,
                                'start' => \Carbon\Carbon::parse($record->start_time)->toTimeString(),
                                'end' => \Carbon\Carbon::parse($record->end_time)->toTimeString(),
                            ]);

                            if($project = @$this->projects[$record->project_id]){
                                $item->project_id = $project->id;
                            }

                            if($technology = @$this->technologies[$record->category_id]){
                                $item->technology_id = $technology->id;
                            }

                            if($record->status == '1'){
                                $item->status = 'P';
                            }elseif($record->status == '2'){
                                $item->status = 'C';
                            }elseif($record->status == '3'){
                                $item->status = 'I';
                            }elseif($record->status == '4'){
                                $item->status = 'L';
                            }

                            $item->works = substr($record->work_id, 0, 191);
                            $item->notes = $record->comments;
                            $item->save();
                        }
                    } catch (\Exception $e) {
                        $this->exception($e);
                    }
                }

            }
        }
    }

    protected function import_late_entries()
    {
        $records = $this->db->table('late_entries')->get();

        foreach ($records as $record) {
            if ($employee = @$this->user_employees[$record->user_id]) {
                try {
                    $late_entry = \App\Models\LateEntry::firstOrNew([
                        'date' => $record->created,
                        'employee_id' => $employee->id
                    ]);
                    $late_entry->elapsed = \App\Helpers\AppHelper::getTimeDiffFormat('09:30:00', \Carbon\Carbon::parse($late_entry->date)->format('H:i:s'), 'H:i:s');
                    $late_entry->status = $record->approved == 1 ? 'A' : 'D';
                    $late_entry->save();
                } catch (\Exception $e) {
                    $this->exception($e);
                }
            }
        }
    }

    protected function import_entries()
    {
        $records = $this->db->table('entries')->get();

        foreach ($records as $record) {
            if ($employee = @$this->user_employees[$record->user_id]) {
                try {
                    $model = \App\Models\Entry::firstOrCreate([
                        'employee_id' => $employee->id,
                        'date' => $record->date,
                    ]);
                    $model->start = \Carbon\Carbon::parse($record->time_in)->toTimeString();
                    $model->end = \Carbon\Carbon::parse($record->time_out)->toTimeString();
                    $model->inip = $record->time_in_ip;
                    $model->outip = $record->time_out_ip;
                    $model->save();

                } catch (\Exception $e) {
                    $this->exception($e);
                }
            }
        }
    }

    private function import_permissions()
    {
        $records = $this->db->table('permissions')->get();

        foreach ($records as $record) {
            if ($employee = @$this->user_employees[$record->user_id]) {
                try {
                    $model = \App\Models\Userpermission::firstOrNew([
                        'employee_id' => $employee->id,
                        'date' => $record->date,
                    ]);
                    $model->start = \Carbon\Carbon::parse($record->from_time)->toTimeString();
                    $model->end = \Carbon\Carbon::parse($record->to_time)->toTimeString();
                    $model->reason = $record->reason;
                    $model->remarks = $record->remarks;
                    if ($record->approved == 1) {
                        $model->status = 'A';
                    } else if ($record->approved == 2) {
                        $model->status = 'D';
                    } else {
                        $model->status = 'P';
                    }

                    $model->save();

                } catch (\Exception $e) {
                    $this->exception($e);
                }
            }
        }
    }

    private function import_leaves()
    {
        $records = $this->db->table('leaves')->get();

        foreach ($records as $record) {
            if ($employee = @$this->user_employees[$record->user_id]) {
                try {
                    $model = \App\Models\Leave::firstOrNew([
                        'employee_id' => $employee->id,
                        'start' => $record->date,
                    ]);
                    $sub_leaves = $this->db->table('sub_leaves')->where('leave_id', $record->id)->oldest('date')->get();
                    foreach ($sub_leaves as $sub) {
                        $model->end = $sub->date;
                    }
                    $model->days = $record->days;
                    $model->reason = $record->reason;
                    $model->remarks = $record->remarks;
                    if ($record->approved == 1) {
                        $model->status = 'A';
                    } else if ($record->approved == 2) {
                        $model->status = 'D';
                    } else {
                        $model->status = 'P';
                    }
                    $model->save();

                    $this->leaves[$record->id] = $model;

                    foreach ($sub_leaves as $sub) {
                        $leaveitems = new App\Models\LeaveItem();
                        $leaveitems->leave_id = $model->id;
                        $leaveitems->date = $sub->date;
                        $leaveitems->days = $sub->day;
                        if ($sub->status == 'C') {
                            $leaveitems->leavetype_id = App\Models\Leavetype::getCasual()->id;
                        } else if ($sub->status == 'P') {
                            $leaveitems->leavetype_id = App\Models\Leavetype::getPaid()->id;
                        }
                        $leaveitems->save();
                    }

                } catch (\Exception $e) {
                    $this->exception($e);
                }
            }
        }
    }

    private function import_compensations()
    {
        $records = $this->db->table('compensations')->get();

        \App\Models\Compensation::query()->forceDelete();

        foreach ($records as $record) {
            if ($employee = @$this->user_employees[$record->user_id]) {
                try {
                    $compensation = new App\Models\Compensation();
                    $compensation->employee_id = $employee->id;
                    $compensation->date = $record->date;
                    $compensation->days = $record->days;
                    $compensation->reason = $record->comments;
                    $compensation->type = $record->type;
                    $compensation->save();

                    $this->compensations[$record->id] = $compensation;

                } catch (\Exception $e) {
                    $this->exception($e);
                }
            }
        }

        $comp_leavetype_id = App\Models\Leavetype::getCompensation()->id;

        $leaves = $this->db->table('leaves')->where('compensation_id', '!=', '')->get();
        foreach ($leaves as $leave) {
            $compensation_ids = unserialize($leave->compensation_id);

            foreach ($compensation_ids as $compensation_id) {
                $compensation = $this->compensations[$compensation_id];

                if($userLeave = @$this->leaves[$leave->id]){
//                    $leaveitem = $userLeave->leaveitems()->where('leavetype_id', '!=', $comp_leavetype_id)->first();
                    $leaveitem = \App\Models\LeaveItem::firstOrCreate([
                        'leave_id' => $userLeave->id,
                        'date' => $compensation->date,
                        'days' => $compensation->days,
                        'leavetype_id' => $comp_leavetype_id,
                    ]);

                    $leaveitem->compensates()->attach($userLeave->id, ['compensation_id' => $compensation->id, 'days' => $compensation->days]);

                    $userLeave->days = $userLeave->days + $compensation->days;
                    $userLeave->compensate = 1;
                    $userLeave->save();
                }
            }
        }
    }

    protected function exception(\Exception $e)
    {
        if (!config('app.debug')) {
            app('sentry')->captureException($e);
        } else {
            echo '<pre>';
            print_r($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            exit;
        }
    }
}
