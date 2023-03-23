<?php

namespace App\Models;

use App\Helpers\CustomfieldHelper;
use App\Helpers\EmployeeHelper;
use Image;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Carbon\Carbon;
use Storage;
use Validator;
use App\Models\UserSettings;

class Employee extends BaseModel
{

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'employees';

    use SoftCascadeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'employeetype', 'gender', 'dob', 'designation_id', 'photo'];

    protected $softCascade = ['user'];

    public static $types = ['P' => 'Permanent', 'T' => 'Trainee'];

    public static $gender = ['M' => 'Male', 'F' => 'Female'];

    public static $options = ['1' => 'Yes', 'F' => 'No'];

    const PHOTO_UPLOAD_PATH = 'uploads/profile/original';
    const PHOTO_SMALL_UPLOAD_PATH = 'uploads/profile/small';

    protected $casts = [
        'casual_count_per_year' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->morphMany('App\Models\Schedule', 'scheduletypes' , 'model_type' , 'model_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'id', 'lead_id');
    }

    public function teamMerber()
    {
        return $this->belongsTo(TeamMember::class, 'id', 'teammate_id');
    }

    public function idp()
    {
        return $this->belongsTo(IDP::class, 'id', 'employee_id');
    }

    public function officetiming()
    {
        return $this->belongsTo(Officetiming::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function lecture()
    {
        return $this->hasMany(Lecture::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function late_entries()
    {
        return $this->hasMany(LateEntry::class);
    }

    public function userpermissions()
    {
        return $this->hasMany(Userpermission::class);
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    public function getAvatarAttribute()
    {
        return $this->photo ? $this->getSmallImage() : Setting::fetch('LOGO_LIGHT_ICON');
    }

    protected function getSmallImage()
    {
        return asset('storage/' . str_replace(self::PHOTO_UPLOAD_PATH, self::PHOTO_SMALL_UPLOAD_PATH, $this->photo));
    }

    public function leaveitems()
    {
        return $this->hasManyThrough(LeaveItem::class, Leave::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function compensation()
    {
        return $this->hasMany(Compensation::class, 'employee_id');
    }
    
    public function evaluations(){
        return $this->hasMany(Evaluation::class);
    }

    public function assessments(){
        return $this->hasMany(Assesment::class);
    }

    public function getShortnameAttribute()
    {
        return head(explode(' ', trim($this->name)));
    }

    public function getDesignationNameAttribute()
    {
        return @$this->designation->name;
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->dob)->age + 1;
    }

    public function getLeaveCount($year = null, $month = null, $scope = null)
    {
        $year = !$year ? Carbon::now()->year : $year;

        $query = $this->leaveitems()->whereYear('date', $year);

        if ($scope) {
            $query->$scope();
        }

        if ($month) {
            $query->whereMonth('date', $month);
        }
        
        $query->notDeclined();

        return $query->sum('leaveitems.days');
    }
    
    public function getTotalLeaveCount($year = null, $month = null)
    {
        return $this->getLeaveCount($year, $month) - $this->getDeclinedCount();
    }

    public function getCasualCount($year = null, $month = null)
    {
        return $this->getLeaveCount($year, $month, 'casual');
    }
   
    public function getPendingCount($year = null, $month = null)
    {
        return $this->getLeaveCount($year, $month, 'pending');
    }

    public function getPaidCount($year = null, $month = null)
    {
        return $this->getLeaveCount($year, $month, 'paid');
    }
    
    public function getDeclinedCount($year = null, $month = null)
    {
        return $this->getLeaveCount($year, $month, 'declined');
    }

    public function getCompensationCount($year = null, $month = null)
    {
        return $this->getLeaveCount($year, $month, 'compensation');
    }

    /* Get the Count of Available Permission Compensations  */
    public function getAvailablePermissionCompensationCount($year = null)
    {
        $year = !$year ? Carbon::now()->year : $year;
        return $this->compensation()->where('type','P')->whereYear('date', $year)->available()->sum('compensations.days');
    }

    public function getAllowedCasualCount($year = null)
    {
        $year = !$year ? Carbon::now()->year : $year;
        return @$this->casual_count_per_year[$year] ?: 0;
    }

    public function getRemainingCasualCount($year = null)
    {
        return $this->getAllowedCasualCount($year) - $this->getCasualCount($year);
    }

    public function getAllowedPermissionCount()
    {
        return Setting::fetch('MAX_ALLOWED_PERMISSION');
    }

    public function getTimerStartedAttribute()
    {
        if($this->employeetype == 'P'){
            return $this->reports()->where('date', Carbon::now()->toDateString())->progress()->first();
        }else{
            return $this->entries()->where('date', Carbon::now()->toDateString())->whereNull('end')->approved()->first();
        }
    }
    
    public function getTraineeCanRequestTimerAttribute()
    {
        return $this->entries()->where('date', Carbon::now()->toDateString())->whereNotNull('start')->whereNotNull('end')->first();
    }

    public function getElapsedTimeAttribute()
    {
        $elpased = null;

        if ($entry = $this->timerStarted) {
            $elpased = gmdate('H:i:s', Carbon::now()->diffInSeconds($entry->start));
        }

        return $elpased;
    }

    public function getAvailableCompensationCount($year = null)
    {
        $Existing_compensations = $this->getExisingCompensationCount($year);
        $Used_compensations = $this->getCompensationCount($year);
//        $Used_compensations = Compensation::join('compensates', 'compensations.id', '=', 'compensation_id')
//                ->where('employee_id', $this->id)->whereYear('date', Carbon::now()->year)->sum('compensates.days');
        $Remaining = ($Existing_compensations - $Used_compensations);
        return $Remaining > 0 ? $Remaining : 0;
    }

    public function getExisingCompensationCount($year = null) {
        $year = !$year ? Carbon::now()->year : $year;
        return $this->compensation()->whereYear('date', $year)->where('type', 'L')->where('is_paid',0)->sum('compensations.days');
    }

    public static function findEmployeeById($id)
    {
        $employee = self::find($id);
        $employee->appendCustomFields();

        return $employee;
    }

    public function appendCustomFields()
    {
        if ($cfval = $this->cfval) {
            foreach ($cfval as $column => $value) {
                $this->$column = $value;
            }
        }

        $this->casual_count_this_year = $this->getAllowedCasualCount();
    }

    public function getDailyReportChartData($year, $month)
    {
        return (new EmployeeHelper($this))->getDailyReportChartData($year, $month);
    }

    public function getGendernameAttribute()
    {
        return @self::$gender[$this->gender];
    }

    public function scopeTrainee($query)
    {
        return $query->where('employeetype', 'T');
    }

    public function scopePermanent($query)
    {
        return $query->where('employeetype', 'P');
    }

    public function scopeActive($query, $active = 1)
    {
        return $query->whereHas('user', function ($q) use ($active){
            $q->where('active', $active);
        });
    }

    public function scopeTeamLead($query, $active = 1)
    {
        return $query->whereHas('user', function ($q) use ($active){
            $q->where('isTeamLead', $active);
        });
    }

    public function scopeTeamMembers($query, $active = 0)
    {
        return $query->whereHas('user', function ($q) use ($active){
            $q->where('isTeamLead', $active);
        });
    }
    
    public function saveProfile($request)
    {
        //Save profile
        $form_data = CustomfieldHelper::getFormDataByModule($request->except(['_token', 'designation', 'email', 'active']), self::class);
        $this->_save_employee($request, $form_data['form']);

        if($custom = @$form_data['custom']){
            $this->save_customdata($custom);
        }
    }

    public static function getProfileRules()
    {
        $rules = [
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'photo' => 'image',
        ];

        //Custom fields validation
        CustomfieldHelper::appendCustomModuleRules( self::class, $rules);

        return $rules;
    }

    protected function _save_employee($request, $form_data)
    {
        if ($request->hasFile('photo')) {
            //store original and resized image
            $ext = $request->photo->getClientOriginalExtension();
            $image_name = time().'.'.$ext;

            $path = self::PHOTO_UPLOAD_PATH;

            Storage::disk('public')->putFileAs($path, $request->photo, $image_name);

            $form_data['photo'] = $path.'/'.$image_name;

            $img = Image::make($request->photo);
            $img->resize(150, 150)->encode($ext, 80);
            Storage::disk('public')->put(self::PHOTO_SMALL_UPLOAD_PATH.'/'.$image_name, $img);
            //end
        }

        if(isset($form_data['casual_count_this_year'])){
            $casual_count_per_year = $this->casual_count_per_year ?: [];
            $casual_count_per_year[Carbon::now()->year] = $form_data['casual_count_this_year'];
            $this->casual_count_per_year = $casual_count_per_year;
        }

        $this->fill($form_data);
        $this->save();

        $this->saveDefaultEmployeeSetting();
    }

    private function saveDefaultEmployeeSetting()
    {   
        $usersetting_name = UserSettings::$defaultSettings;
 
        foreach($usersetting_name as $name => $value)
        {
            $usersetting = UserSettings::where('user_id',$this->user_id)->where('name',$name)->exists();

            if(!$usersetting)
            {
                UserSettings::create([
                    'name' => $name,
                    'value' => $value,
                    'user_id' => $this->user_id,
                ]);
            }
        }
    }

    public function save_customdata($form_data, $validate = true)
    {
        CustomfieldHelper::storeCustomfieldData(self::class, $form_data, $this->id, $validate);
    }

    public function exlcudeFromBreaks()
    {
        return (in_array($this->id, Setting::fetch('EXCLUDE_EMPLOYEE_FROM_BREAKS')));
    }

    public function exlcudeFromReports()
    {
        return (in_array($this->id, Setting::fetch('EXCLUDE_EMPLOYEE_FROM_REPORTS')));
    }

    public function getCurrentExperienceAttribute()
    {
        if($joined = @$this->cfval->employee_joinedon){
            $val = '';

            $diff = Carbon::parse($joined)->diff(Carbon::now());

            if($y = $diff->y){
                $val .= $y.' '.str_plural('year', $y).' ';
            }

            if($m = $diff->m){
                $val .= $m.' '.str_plural('month', $m);
            }

            return $val;
        }

        return '-';
    }

    public function employeetempcard()
    {
        return $this->belongsTo(Employeetempcard::class);
    }
}
