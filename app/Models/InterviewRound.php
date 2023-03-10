<?php

namespace App\Models;

use App\Helpers\CustomfieldHelper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class InterviewRound extends BaseModel {

    use SoftDeletes;
    use Notifiable;
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'interview_rounds';
    protected $dates = ['deleted_at'];

    public static $rounds = ['1' => 'Telephonic Interview', '2' => 'System Assessment', '3' => 'Final Technical', '4' => 'Final HR'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'interviewcall_id','round','datetime','remarks','status','employee_id' 
    ];

    public function candidate() 
    {
        return $this->belongsTo(InterviewCandidate::class,'interview_candidate_id');
    }

    public function status()
    {
        return $this->belongsTo(Interviewstatus::class,'interview_status_id');
    }

    public function interview_call()
    {
        return $this->belongsTo(InterviewCall::class,'interviewcall_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function saveStatus($request)
    {
        $form_data = CustomfieldHelper::getFormDataByModule($request, self::class);
        
        if($custom = @$form_data['custom']){
            $this->save_customdata($custom);
        }
    }

    public function save_customdata($form_data , $validate = true)
    {
        CustomfieldHelper::storeCustomfieldData(self::class, $form_data, $this->id, $validate);
    }

    public function custom_field_val()
    {
        return $this->hasMany(CustomFieldValue::class, 'model_id');
    }

    public function routeNotificationForSlack() {
        return env('LOG_SLACK_WEBHOOK_URL');
    }
}
