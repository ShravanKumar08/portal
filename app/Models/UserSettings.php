<?php

namespace App\Models;

use App\Helpers\AppHelper;
use App\Helpers\CustomfieldHelper;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Storage;
use Mail;
use Illuminate\Support\Str;

class UserSettings extends BaseModel 
{
    const GITHUB_CREDENTIALS = 'GITHUB_CREDENTIALS';

    protected $table = 'usersettings';
    public $timestamps = true;
    public $incrementing = false;

    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id','name','value'];

    public static $defaultSettings = [
        'GITHUB_CREDENTIALS' => '{"username":"","personalaccesstoken":""}', 
        'CALENDAR_LAST_VALUE' => '{"filters":["leaves","holidays","permissions"]}' , 
        'THEME_COLOR' => 'default-dark'
    ];
    
    public static function getInvisibleNames()
    {
        return [
            'CALENDAR_LAST_VALUE',
            'REPORT_NOTIFICATION_EMAIL'
        ];
    }

    public static function boot()
    {
        parent::boot();

        self::updated(function($model){
            try{
                if(request()->session()->has("SETTINGS.{$model->name}")){
                    request()->session()->forget("SETTINGS.{$model->name}");
                }
            }catch (\Exception $e){
                //
            }
        });
    }

    public function getUserSettingNameAttribute()
    {
        return Str::title(str_replace(['_'], [' '], $this->name));
    }

    public static function fetch($name) {
        return self::where('name', $name)->where('user_id', \Auth::user()->id)->first()->value ?? null;
    }
}