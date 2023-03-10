<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class MinIntervalPeriod implements Rule {

    protected $min;
    protected $end;
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($end, $min) {
        $this->end = $end;
        $this->min = $min;
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
        $time = explode(':', $this->min);
        $minTime = ($time[0]*3600) + ($time[1]*60) + $time[2];
        return $startTime->diffInSeconds($finishTime) >= $minTime;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return "The interval between start and end should not be lesser than {$this->min}";
    }

}