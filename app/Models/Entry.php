<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Entry extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'entries';

    public $total_in_hours = null;
    public $total_out_hours = null;
    public $total_hours = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'date', 'start', 'end', 'inip', 'outip'
    ];
    
    public static $status = ['P' => 'Pending', 'A' => 'Approved', 'D' => 'Declined'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }

    public function entryitems()
    {
        return $this->hasMany(Entryitem::class);
    }

    public function saveForm($request)
    {
        $data = $request->except(['_token']);
        $this->fill($data);
        $this->save();
    }

    public function getEntryItems()
    {
        $entryitems = $this->entryitems()->oldest('datetime')->get();

        $total_in_hours = $total_out_hours = $total_hours = '0:00';

        $items = $entryitems->map(function ($item, $key) use ($entryitems, &$total_in_hours, &$total_out_hours, &$total_hours){
            $item->start = Carbon::parse($item->datetime)->format('h:i A');
            $item->end = null;
            $item->elapsed = null;

            $next = $entryitems->get($key + 1);

            if($next && ($next->inout != $item->inout)){
                $item->end = Carbon::parse($next->datetime)->format('h:i A');
                $item->elapsed = AppHelper::getTimeDiffFormat($item->start, $item->end, 'h:i A', 'H:i', false);

                $elapsed_hour = Carbon::createFromFormat('H:i', $item->elapsed)->format('H');
                $elapsed_min = Carbon::createFromFormat('H:i', $item->elapsed)->format('i');

                //Reference variables for $btotal_in_hours and $total_out_hours
                $hour_variable = &${$item->inout == 'I' ? 'total_in_hours' : 'total_out_hours'};
                $hour_variable = (new Carbon($hour_variable))->addHours($elapsed_hour)->addMinutes($elapsed_min)->format('H:i');

                //Total Hours
                $total_hours = (new Carbon($total_hours))->addHours($elapsed_hour)->addMinutes($elapsed_min)->format('H:i');
            }

            return $item;
        });

        $this->total_out_hours = $total_out_hours;
        $this->total_in_hours = $total_in_hours;
        $this->total_hours = $total_hours;

        try{
            if($this->start && $this->end){
                $this->total_hours = AppHelper::getTimeDiffFormat($this->start, $this->end, 'H:i', 'H:i', false);
                $this->total_in_hours = AppHelper::getTimeDiffFormat($this->total_hours, $this->total_out_hours, 'H:i:s', 'H:i', false);
            }
        }catch (\Exception $e){
            //
        }

        return $items;
    }
    
    public function getStatusnameAttribute()
    {
        return @self::$status[$this->status];
    }
    
    public function scopeTraineeEndtime($query)
    {
        return $query->where('end', null)->whereHas('employee', function ($q){
            $q->where('employeetype', 'T');
        });
    }
    
    public function pendingTraineeNotificationMail()
    {
        $mail = $this->getMailObject();

        if ($mail) {
            $mail->queue(new \App\Mail\EntriesNotification($this));
        }
    }
    
    protected function getMailObject()
    {
        return Setting::getMailObject('REPORT_NOTIFICATION_EMAIL');
    }
    
    public function remarksMail()
    {
        $mail = Setting::getMailObject('REPORT_NOTIFICATION_EMAIL');

        if($mail){
            $mail->queue(new \App\Mail\EntryStatusNotification($this));
        }
    }
    
}
