<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewCandidate extends BaseModel {

    use SoftDeletes;
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'interview_candidates';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
         'name','email','mobile','resume','martial_status','permanent_location','designation_id','gender','current_designation','technology'   
    ];
    // public static $types = ['P' => 'Permanent', 'T' => 'Trainee'];
    
    // public static $genders = ['M' => 'Male','F'=>'Female'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    


    public function designation()
    {
        return $this->belongsTo(Designation::class,'designation_id');
    }


}
