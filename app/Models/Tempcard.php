<?php

namespace App\Models;

use Carbon\Carbon;

class Tempcard extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'tempcards';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'employee_id', 'date', 'tempcard','active'
    ];

    public function employee() 
    {
        return $this->belongsTo(Employee::class);
    }

    public static function getRules($request, $id = null)
    {
        $Employee = Employee::find($request->employee_id);
        $date = Carbon::parse($request->date);
        return [
            'employee_id' => 'required',
            'date' => 'required',
            'tempcard' => 'required',
        ];
    }

    public function saveForm($request)
    {
        $data = $request->except(['_token']);
        $this->fill($data);
        $this->save();
    }
}
