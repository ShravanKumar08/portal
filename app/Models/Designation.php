<?php
namespace App\Models;
class Designation extends BaseModel {
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'designations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'active'
    ];
    
    public static $types = ['P' => 'Permanent', 'T' => 'Trainee'];
    
    public static $genders = ['M' => 'Male','F'=>'Female'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
    public function employees() 
    {
        return $this->hasMany(Employee::class);
    }
    public function getEmployeeCountAttribute()
    {
        return $this->employees()->count();
    }
    
     public function getActiveEmployeeCountAttribute()
    {
        return $this->employees()->active()->count();
    }
    
    public function getInactiveEmployeeCountAttribute()
    {
        return $this->employees()->active(0)->count();
    }
    
}