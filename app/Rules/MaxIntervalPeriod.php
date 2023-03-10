<?php

namespace App\Rules;

use App\Helpers\AppHelper;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class MaxIntervalPeriod implements Rule {

    protected $max;
    protected $end;
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($end, $max) {
        $this->end = $end;
        $this->max = $max;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $this->attribute = $attribute;
        $startTime = Carbon::parse($value);
        $finishTime = Carbon::parse($this->end);
        $time = explode(':', $this->max);
        $maxTime = ($time[0]*3600) + ($time[1]*60) + $time[2];
        if($startTime > $finishTime){
            $finishTime = Carbon::parse($this->end)->addDay(1);
        }
        return $startTime->diffInSeconds($finishTime) <= $maxTime;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return "The interval between start and end should not be greater than {$this->max}";
    }

}
