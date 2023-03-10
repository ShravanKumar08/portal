<?php

namespace App\Models;

use App\Scopes\EmployeeScope;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BaseModel extends Model {

    use SoftDeletes, Uuids;

    public $cfvalues = null;

    protected static function boot()
    {
        parent::boot();

        if(auth()->check() && in_array(request()->route()->getPrefix(), ['/employee','/trainee'])){
            static::addGlobalScope(new EmployeeScope(auth()->user()->employee_id, auth()->user()->id));
        }
    }

    public function getCfvalAttribute()
    {
        if(!$this->cfvalues) {
            $data = $this->hasMany(CustomFieldValue::class, 'model_id');
            $this->cfvalues = (object)$data->with('custom_field')->get()->pluck('value', 'custom_field.name')->toArray();
        }

        return $this->cfvalues;
    }

    public function getCustomFields()
    {
        return CustomField::where('module_type', self::class)->get();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'P');
    }
    
    public function scopePendingApproved($query)
    {
        return $query->whereIn('status', ['P', 'A']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'A');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'D');
    }

    public function scopeNotDeclined($query)
    {
        return $query->where('status', '!=', 'D');
    }

    public function scopeMonthYear($query, $col, $year, $month)
    {
        return $query->whereMonth($col, $month)->whereYear($col, $year);
    }

    public function scopeActive($query) {
        return $query->where('active', 1);
    }

    public function scopeMine($query, $column = 'user_id') {
        if($user = \Auth::user()){
            return $query->where($column, $user->id);
        }
    }
}