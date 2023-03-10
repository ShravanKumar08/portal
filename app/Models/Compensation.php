<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Compensation extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'compensations';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'days', 'reason', 'type', 'employee_id'];

    public static $status = ['F' => 'Fully Used', 'P' => 'Partially Used', 'N' => 'Not Used', 
        'PD'=>'Paid', 'C' => 'Converted To Paid','L' => 'Taken as Leave','CP' => 'Taken as Permission', 'NC' => 'No Compensation'];
    
    public static $types = ['L' => 'Leave', 'P' => 'Permission'];
    
    public static $days = ['0.5' => 'Half a day', '1' => 'Full day'];

    public function scopeAvailable($query)
    {
        return $query->whereRaw("compensations.days > (select IFNULL(sum(days), 0) from compensates where compensation_id = compensations.id)");
    }

    public function getAvailableDaysAttribute()
    {
        $used_compensations = Compensation::join('compensates', 'compensations.id', '=', 'compensation_id')->where('type', 'L')->where('compensations.id', $this->id)->sum('compensates.days');
        return ($this->days - $used_compensations);
    }
    
    public function getCompensatedaysAttribute()
    {
        return $this->date." ({$this->pivot->days})";
    }
    
    public function userpermissions() {
        return $this->morphedByMany(Userpermission::class, 'compensates')->withPivot('days');
    }
    public function leaveitems() {
        return $this->morphedByMany(LeaveItem::class, 'compensates')->withPivot('days');
    }
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusAttribute()
    {
        $employeeCompensates = $this->where('employee_id',$this->employee->id)->where('is_paid', 0);
        $sumOfCompensates = $employeeCompensates->sum('days');
        $totalCompensateDays = DB::table('compensates')->whereIn('compensation_id',$employeeCompensates->pluck('id'))->sum('days');

        $status = 'N';

        if($this->is_paid == 1){
            $status = 'PD';
        } else if($this->is_paid == 2){
            $status = 'C';
        }else if($this->is_paid == 3){
            $status = 'L';
        } else if($this->is_paid == -1){
            $status = 'NC';
        } else {
            if($this->userpermissions)
                $countofdays = $this->userpermissions->count();
            if($this->leaveitems)
                $sumofdays = $this->leaveitems->sum("days");
          
            if(@$countofdays){
                $status =  'CP';
            }else if(@$sumofdays){
                if($totalCompensateDays == $sumOfCompensates)
                    $status =  'F';
                else if($this->days == $sumofdays) 
                     $status =  'F';
                elseif($this->days > $sumofdays && $sumofdays != 0) 
                    $status = 'P';
            }else{
                $status = 'N';
            }
        }
        return $status;
    }
    
    public function getStatusnameAttribute() {
        return @self::$status[$this->status];
    }
    
    public function getLeaveDaysAttribute()
    {
       return $this->leaveitems->implode('leavedays', ', ');
    }
    
     public static function getRules($request, $id = null) {
         return [
                'employee_id' => 'required',
                'date' => 'required',
                //'days' => 'required',
                'reason' => 'required',
                'type' => 'required',
            ];
    }
    
    public function saveForm($request) {
        $data = $request->except(['_token']);
        if($request->type === 'P'){
           $data['days'] = 1;
        }
        $this->fill($data);
        $this->save();
       
    }

    public function scopeStatus($query, $status)
    {
        if($status == 'PD'){
            $query->where('is_paid', 1);
        } elseif($status == 'C'){
            $query->where('is_paid', 2);
        } elseif($status == 'N'){
            $query->doesntHave('leaveitems')->where('is_paid', 0);
        } elseif($status == 'NC'){
            $query->where('is_paid', -1);
        } else {
            $query->whereHas('leaveitems', function($q) use ($status){
                if($status == 'F'){
                    $q->havingRaw('sum(leaveitems.days) = compensations.days');
                }elseif($status == 'P'){
                    $q->havingRaw('(sum(leaveitems.days) < compensations.days AND sum(leaveitems.days) != 0)');
                }
            });
        }

        return $query;
    }
}
