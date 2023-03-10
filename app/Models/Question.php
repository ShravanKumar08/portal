<?php

namespace App\Models;

class Question extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'questions';
    protected $fillable = ['name','type','grade_id','answer','options','duration','opt_count'];
    public static $question_types = ['O' => 'Objective', 'D' => "Description"];
    public static $question_minutes = ['1' => '1','2' => '2','3' => '3','4' => '4','5' => '5'];
    public static $option_counts = ['2' => '2','3' => '3','4' => '4','5' => '5','6' => '6'];

    public function platforms(){
        return $this->belongsToMany('App\Models\Platform');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}