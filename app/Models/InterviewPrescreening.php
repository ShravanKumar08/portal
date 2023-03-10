<?php

namespace App\Models;

use App\Helpers\CustomfieldHelper;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewPrescreening extends BaseModel {

    use SoftDeletes;
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'interview_prescreening';
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'id', 'name','phone','email','location','remarks','deleted_at' 
    ];

    public function saveStatus($request)
    {
        //Save Candidate profile
        $form_data = CustomfieldHelper::getFormDataByModule($request, self::class);
        
        if($custom = @$form_data['custom']){
            $this->save_customdata($custom);
        }
    }

    public function save_customdata($form_data, $validate = true)
    {
        CustomfieldHelper::storeCustomfieldData(self::class, $form_data, $this->id, $validate);
    }

    public static function findPrescreeningById($id)
    {
        $prescreen = self::find($id);
        $prescreen->appendCustomFields();
        return $prescreen;
    }

    public function appendCustomFields()
    {
        if ($cfval = $this->cfval) {
            foreach ($cfval as $column => $value) {
                $this->$column = $value;
            }
        }
       
    }

    public function custom_field_val()
    {
        return $this->hasMany(CustomFieldValue::class, 'model_id');
    }
}
