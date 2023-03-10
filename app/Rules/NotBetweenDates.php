<?php

namespace App\Rules;

use App\Models\Leave;
use App\Models\Userpermission;
use Illuminate\Contracts\Validation\Rule;

class NotBetweenDates implements Rule
{
    protected $table;
    protected $employee_id;
    protected $id;
    protected $attribute;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $employee_id, $id)
    {
        $this->table = $table;
        $this->employee_id = $employee_id;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        if($this->table == 'leave'){
            $query = Leave::query()->withoutGlobalScope('permanent');

            if($this->id){
                $query->where('id', '!=', $this->id);
            }

            $query->where('employee_id', $this->employee_id);
            $query->where('start', '<=', $value);
            $query->where('end', '>=', $value);
            $query->where('status', '!=', 'D');

            return !$query->exists();
        }elseif ($this->table == 'permission'){
            $query = Userpermission::query();

            if($this->id){
                $query->where('id', '!=', $this->id);
            }

            $query->where('employee_id', $this->employee_id);
            $query->where('date', $value);
            $query->where('status', '!=', 'D');

            return !$query->exists();
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
        if($this->table == 'leave'){
            return "Leave already exists in this {$this->attribute} date";
        }elseif ($this->table == 'permission'){
            return "Permission already exists in this {$this->attribute}";
        }

        return 'The validation error message.';
    }
}
