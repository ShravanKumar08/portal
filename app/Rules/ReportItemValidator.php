<?php

namespace App\Rules;

use App\Helpers\AppHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Technology;

class ReportItemValidator implements Rule
{

    protected $error_message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!$this->_validate_rows_mismatch()){
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }

    /**
     * Report start and end time mismatch between rows
     * @return bool
     */
    private function _validate_rows_mismatch()
    {
       
        $report_items = \App\Models\ReportItem::where('report_id',request()->report_id)
                ->where('end','>', request()->start)
                ->where('start','<', request()->end)
                ->where('id','!=', request()->id)
                ->exists();
        
        if($report_items){
            $this->error_message = 'Invalid timings!! Time already exists';
        }

        if(request()->projectname == null && request()->technology_id != null){
            $tech = Technology::find(request()->technology_id);

            if($tech->exclude == 0){
                $report_items = true; //like reverse
                $this->error_message = 'Project name is required';
            }
        }

                
        return $report_items == false;
    }

    
}
