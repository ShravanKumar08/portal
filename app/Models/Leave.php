<?php

namespace App\Models;

use App\Mail\LeaveRequest;
use App\Rules\NotBetweenDates;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\LateEntry;

class Leave extends BaseModel implements Auditable {

    use \OwenIt\Auditing\Auditable,
        SoftCascadeTrait;

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'leaves';

    protected $softCascade = ['leaveitems'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('permanent', function (Builder $builder) {
            $builder->permanent();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_id', 'start', 'end', 'days', 'reason', 'remarks', 'status', 'compensate' , 'at_training_period'];

    public static $status = ['A' => 'Approved', 'D' => 'Declined', 'P' => 'Pending'];

    public function scopePermanent($query)
    {
        return $query->where('at_training_period', 0);
    }
    
    public function scopeTrainee($query)
    {
        return $query->where('at_training_period', 1);
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function leaveitems() {
        return $this->hasMany(LeaveItem::class);
    }

    public function getHalfdayAttribute() {
        return @$this->leaveitems()->where('days', 0.5)->first()->date;
    }

    public function getStatusnameAttribute()
    {
        return @self::$status[$this->status];
    }

    public static function getRules($request, $id = null) {
        return [
            'employee_id' => 'required',
            'days' => 'required|numeric',
            'start' => [
                'required', 
                'date', 
                'before_or_equal:end', 
                new NotBetweenDates('leave', $request->employee_id, $id),
                function ($attribute, $value, $fail) use ($request){
                    if(Carbon::parse($request->end)->year != Carbon::parse($value)->year){
                        $fail('You can apply leave within one year cycle');
                    }
                },
            ],
            'end' => ['required', 'date', 'after_or_equal:start', new NotBetweenDates('leave', $request->employee_id, $id)],
            'reason' => 'required',
            'status' => 'required',
        ];
    }

    public function saveForm($request) {
        $data = $request->except(['_token']);
        $data['days'] = $request->days;
        $this->fill($data);
        $this->save();
        
        $this->leaveitems()->delete();

        $dates = explode(",", $request->leavedates);
        $has_digits = (floor(@$request->days) != @$request->days); //check 0.5 days

        foreach ($dates as $date) {
            $leaveitem = new LeaveItem();
            $datas['date'] = $date;
            $datas['leave_id'] = $this->id;
            $datas['days'] = ($has_digits && @$request->halfday == $date) ? 0.5 : 1;
            $leaveitem->fill($datas);
            $leaveitem->save();

            if($leaveitem->days == 1){
                Report::where('date', $date)->where('employee_id', $this->employee_id)->where('status', 'P')->delete();
            }
        }

        $this->processLeaveRequest();
        $this->mailTo();
    }

    public function mailTo() {
        $mail = Setting::getMailObject('LEAVE_NOTIFICATION_MAIL');

        if($mail){
            $mail->queue(new LeaveRequest($this));
        }
    }
    
    public function remarksMail()
    {
        $mail = Setting::getMailObject('LEAVE_NOTIFICATION_MAIL');

        if($mail){
            $mail->queue(new \App\Mail\LeaveNotification($this));
        }
    }

    public function processLeaveRequest() {
        $employee = $this->employee;
        
        $leave_apply_year= Carbon::parse($this->start)->year;
        
        $e_casual_count = $employee->getCasualCount($leave_apply_year);
        $e_allowed_count = $employee->getAllowedCasualCount($leave_apply_year);

        $split_paid = null;

        foreach ($this->leaveitems as $leaveitem) {
            $leavetype_id = null;

            //When approves
            if ($this->status == 'A') {
                if($leaveitem->leavetype_id == null){
                    $leavetype_id = Leavetype::getCasual()->id;
                    $e_casual_count += $leaveitem->days;

                    //If greater than allowed casual days
                    if ($e_casual_count > $e_allowed_count) {
                        //For ex: User has 11.5 days and approve 1 day leave, split leave dates to 2 rows -> 0.5 casual and 0.5 paid
                        if ($e_casual_count - $leaveitem->days < $e_allowed_count) {
                            $leaveitem->days = 0.5;
                            $split_paid = $leaveitem->replicate();
                        } else {
                            $leavetype_id = Leavetype::getPaid()->id;
                        }
                    }
                }else{
                    $leavetype_id = $leaveitem->leavetype_id;
                }
            }

            $leaveitem->leavetype_id = $leavetype_id;
            $leaveitem->save();
        }

        if ($split_paid) {
            $split_paid->leavetype_id = Leavetype::getPaid()->id;
            $split_paid->save();
        }

        if($this->compensate){
            if($this->status == 'A'){
                $this->processCompensate();
            }else{
                foreach ($this->leaveitems as $leaveitem) {
                    $leaveitem->compensates()->detach();
                }
            }
        }
        // Remove late entry if leave request is accepted
        if( $this->status == 'A')
            LateEntry::query()->where('employee_id',$this->employee->id)->whereDate('date',$this->start)->delete();
        

    }
    
    protected function processCompensate()
    {
        $compensate_type_id = Leavetype::getCompensation()->id;
        $leave_count = $this->days;

        do{
            $leaveitem = $this->leaveitems()->where('leavetype_id', '!=', $compensate_type_id)->orderBy('date', 'DESC')->first();
            $compensation = Compensation::where('employee_id', $this->employee->id)->available()->first();

            if(!$compensation){
                break;
            }

            $leave_days = $leaveitem->days;
            $compensate_days = $compensation->availableDays;

            if($leave_days <= $compensate_days){
                $leaveitem->leavetype_id = $compensate_type_id;
                $leaveitem->save();
                $leaveitem->compensates()->attach($this->id, ['compensation_id' => $compensation->id, 'days' => $leave_days]);

                $leave_count -= $leave_days;
            }else if($leaveitem->days > $compensation->availableDays){
                //Ex: leave days -> 1 and compensate -> 0.5 -> then split leave items into 2 records
                $leaveitem->days = 0.5;
                $leaveitem->save();

                $replicate_leaveitem = $leaveitem->replicate();
                $replicate_leaveitem->leavetype_id = $compensate_type_id;
                $replicate_leaveitem->save();
                $replicate_leaveitem->compensates()->attach($this->id, ['compensation_id' => $compensation->id, 'days' => 0.5]);

                $leave_count -= 0.5;
            }
        }while($leave_count > 0);
    }
    
    public function transformAudit(array $data): array {
        if (Arr::has($data, 'new_values.employee_id')) {
            $data['new_values']['employee_id'] = $this->employee->name;
        }
        
        if (Arr::has($data, 'old_values.employee_id')) {
            $data['old_values']['employee_id'] = $this->employee->name;
        }
        
        if (Arr::has($data, 'new_values.status')) {
            $data['new_values']['status'] = $this->statusname;
        }
        
        if (Arr::has($data, 'old_values.status')) {
            $data['old_values']['status'] = $this->statusname;
        }

        return $data;
    }

    public function getCompensatedaysAttribute()
    {
        $compensates = [];
        foreach ($this->leaveitems as $leaveitem) {
            $compensates[] = $leaveitem->compensates->count() ? $leaveitem->compensates->pluck('compensatedays')->toArray() : [];
        }

        return $compensates ? implode(',', array_flatten($compensates)) : '';
    }

    public function getLeavedatesAttribute()
    {
        if($this->start != $this->end){
            return Carbon::parse($this->start)->format('d-m-Y').' - '.Carbon::parse($this->end)->format('d-m-Y');
        }else{
            return Carbon::parse($this->start)->format('d-m-Y');
        }
    }
}
