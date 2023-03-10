<?php

namespace App\Models;

use App\Helpers\AppHelper;
use App\Helpers\CustomfieldHelper;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Str;
use Storage;
use Mail;

class Setting extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'settings';
    public $timestamps = true;
    public $incrementing = false;
    
    protected $casts = ['emailparams' => 'array'];

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'value', 'hint', 'fieldtype', 'emailtemplate'];

    public static $default_official_permission = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

    public static $default_official_leave = [1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1];

    public function schedules()
    {
        return $this->morphMany('App\Models\Schedule', 'scheduletypes' , 'model_type' , 'model_id');
    }

    public static function boot()
    {
        parent::boot();

        self::updated(function($model){
            try{
                if(request()->session()->has("SETTINGS.{$model->name}")){
                    request()->session()->forget("SETTINGS.{$model->name}");
                }
            }catch (\Exception $exception){

            }
        });
    }

    public static function fetch($name) {
        if($name == 'THEME_COLOR'){
            if($user_theme = @UserSettings::query()->mine()->where('name', $name)->first()->value){
               return $user_theme;
            }
        }

        return Setting::where('name', $name)->first()->value ?? null;
    }

    public function getSettingNameAttribute()
    {
        return Str::title(str_replace(['_'], [' '], $this->name));
    }

    public function getValueAttribute($value)
    {
        if($this->fieldtype == 'multiselect'){
            return json_decode($value, true) ?: [];
        }elseif ($this->fieldtype == 'file'){
            $value = Storage::disk('public')->exists($value) ? Storage::disk('public')->url($value) : asset($value);
        }

        return $value;
    }

    public function setValueAttribute($value)
    {
        if($this->fieldtype == 'multiselect'){
            $value = json_encode($value, true);
        }

        $this->attributes['value'] = $value;
    }

    public function getIsMailsettingAttribute()
    {
        return in_array($this->name, ['PERMISSION_NOTIFICATION_MAIL', 'LEAVE_NOTIFICATION_MAIL', 'REPORT_NOTIFICATION_EMAIL']);
    }

    public function getisEmailTemplateAttribute()
    {
        return in_array($this->emailtemplate, [1]);
    }
    
    public static function strReplaceEmployeeContent($content, $employee)
    {
        $employee->appendCustomFields();

        $now = Carbon::now();
       
        $content = str_replace(["{employee.tomorrow}", "{other.current_Month_Year}", "{other.current_Year}"], [$now->addDays(1)->toDateString(), $now->format('F Y'), $now->format('Y')], $content);
        
        $employee_fields = [];

        preg_match_all('#\{employee.(.*?)\}#', $content, $matches);
        
        if(isset($matches[1])){
            $employee_fields = array_merge($employee_fields, $matches[1]);
        }
        
        foreach ($employee_fields as $employee_field) {
            $replace = is_array($employee->$employee_field) ? implode(',', $employee->$employee_field) : @$employee->$employee_field;
            $content = str_replace('{employee.'.$employee_field.'}', $replace, $content);
        }
        
        $now = Carbon::now();

        $content = str_replace(["{employee.tomorrow}", "{other.current_Date_Month_Year}", "{other.current_Month_Year}", "{other.current_Year}"], [$now->addDays(1)->toDateString(), $now->format('jS F Y'), $now->format('F Y'), $now->format('Y')], $content);
        
        return $content;
    }

    public static function isOfficialPermissionToday($date = null)
    {
        return self::officialLeavePermissionCheck('permission', $date);
    }

    public static function isOfficialLeaveToday($date = null)
    {
        return self::officialLeavePermissionCheck('leave', $date);
    }

    public static function isOfficialHalfdayLeaveToday($date = null)
    {
        return self::officialLeavePermissionCheck('halfday_leave', $date);
    }

    private static function officialLeavePermissionCheck($mode, $date = null)
    {
        $value = self::fetch('OFFICIAL_PERMISSION_LEAVE_DAYS');
        $now = $date ? Carbon::parse($date) : Carbon::now();

        if($val = @$value[$mode][$now->weekOfMonth]){
            return $now->dayOfWeek == $val['dayOfWeek'] && $val['value'] == 1;
        }

        return false;
    }

    public static function getMailObject($name)
    {
        $emails = AppHelper::getSettingEmails($name);
        
        return AppHelper::getMailObject($emails);
    }
    
    public function scopeEmailTemplate($query, $emailtemplate = 0)
    {
        return $query->where('emailtemplate', $emailtemplate);
    }

    public static function clearThemeSession()
    {
        request()->session()->forget('SETTINGS.THEME_COLOR');
    }
}
