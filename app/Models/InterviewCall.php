<?php

namespace App\Models;

use App\Helpers\CustomfieldHelper;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewCall extends BaseModel {

    use SoftDeletes;
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'interview_calls';
    protected $dates = ['deleted_at'];

    public $roundInf;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'id', 'schedule_date','present_location','present_company','change_reason','interview_candidate_id', 'remarks', 'interview_status_id', 'experience' ,'deleted_at' 
    ];

    public function candidate() 
    {
        return $this->belongsTo(InterviewCandidate::class,'interview_candidate_id');
    }

    public function status()
    {
        return $this->belongsTo(Interviewstatus::class,'interview_status_id');
    }
    
    public function remark()
    {
        return $this->hasMany(InterviewRemark::class,'interview_call_id');
    }

    public function interview_round()
    {
        return $this->hasMany(InterviewRound::class,'interviewcall_id');
    }

    public static function findCandidateById($id)
    {
        $candidate = self::find($id);
        $candidate->appendCustomFields();

        return $candidate;
    }

    public function appendCustomFields()
    {
        if ($cfval = $this->cfval) {
            foreach ($cfval as $column => $value) {
                $this->$column = $value;
            }
        }
       
    }

    public function saveCandidateProfile($request)
    {
        //Save Candidate profile
        $form_data = CustomfieldHelper::getFormDataByModule($request, self::class);
        
        if($custom = @$form_data['custom']){
            $this->save_customdata($custom);
        }
    }

    public function save_customdata($form_data, $validate = true)
    {
        CustomfieldHelper::storeCustomfieldData(self::class, $form_data, $this->id, $validate);
    }
}
