<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class EmployeeScope implements Scope
{
    protected $employee_id;

    protected $user_id;

    public function __construct($employee_id, $user_id)
    {
        $this->employee_id = $employee_id;
        $this->user_id = $user_id;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();

        if(Schema::hasColumn($table, 'employee_id')){
            $builder->where('employee_id', $this->employee_id);
        }

//        if(Schema::hasColumn($table, 'user_id')){
//            $builder->where('user_id', $this->user_id);
//        }
    }
}