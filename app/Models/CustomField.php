<?php

namespace App\Models;

class CustomField extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'custom_fields';

    public static $field_types = [
        'text' => 'text', 'select' => "select", 'textarea' => 'textarea', 'date' => 'date', 'datetime' => 'datetime',
        'time' => 'time', 'increment' => 'increment', 'hidden' => 'hidden'
    ];
    public static $model_types = ["employee" => "employee" , "interviewcall" => "interviewcall", "interviewprescreening" => "interviewprescreening", "interviewround" => "interviewround"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label', 'model_type', 'sort', 'field_type', 'required', 'formgroup', 'select_options', 'default'];

    public function custom_field_values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->sort = CustomField::max('sort') + 1;
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
