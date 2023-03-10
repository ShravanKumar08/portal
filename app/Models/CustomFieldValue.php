<?php

namespace App\Models;

class CustomFieldValue extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'custom_field_values';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['model_id', 'custom_field_id', 'value'];

    public function custom_field()
    {
        return $this->belongsTo(CustomField::class);
    }
}
