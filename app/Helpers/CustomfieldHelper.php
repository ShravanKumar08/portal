<?php

namespace App\Helpers;

use App\Models\CustomField;
use App\Models\CustomFieldValue;

class CustomfieldHelper
{
    const timeFormat = 'H:i';

    const datetimeFormat = 'Y-m-d h:i A';
    const datetimeFormat_JS = 'YYYY-MM-DD hh:mm A';

    const EMPLOYEE_DEVICE_FIELD = 'employee_deviceuserid';

    public static function getCustomfieldsByModule($module, $validate = true)
    {
        $query = CustomField::where('model_type', $module);

        if($validate) {
            $query->whereHas('roles', function ($q){
                $q->whereIn('id', auth()->user()->roles->pluck('id')->toArray());
            });
        }

        return $query->orderBy('sort', 'ASC')->get();
    }

    public static function getFormDataByModule($request, $module)
    {
        $data = [];
        $fields = self::getCustomfieldsByModule($module)->pluck('name')->toArray();

        $values = is_array($request) ? $request: $request->all();
        foreach ($values as $field => $value) {
            $data[in_array($field, $fields) ? 'custom' : 'form'][$field] = $value;
        }

        return $data;
    }

    public static function storeCustomfieldData($module, $data, $model_id, $validate = true)
    {
        $custom_fields = self::getCustomfieldsByModule($module, $validate);

        foreach ($custom_fields as $custom_field) {
            $column = $custom_field->name;
            $custom_field_id = $custom_field->id;

            if($custom_field->field_type == 'increment'){
                $value = self::_get_increment_value($custom_field_id, $module, $model_id);
            }else{
                $value = @$data[$column];
            }

            if($value){
                self::saveCustomField($custom_field_id, $model_id, $value);
            }else{
                CustomFieldValue::where(['custom_field_id' => $custom_field_id, 'model_id' => $model_id])->delete();
            }
        }
    }

    public static function saveCustomField($custom_field_id, $model_id, $value)
    {
        $custom_field_value = CustomFieldValue::firstOrNew([
            'custom_field_id' => $custom_field_id,
            'model_id' => $model_id,
        ]);

        $custom_field_value->value = $value;
        $custom_field_value->save();

        return $custom_field_value;
    }

    protected static function _get_increment_value($custom_field_id, $module, $model_id)
    {
        $custom_field_value = CustomFieldValue::where(['custom_field_id' => $custom_field_id, 'model_id' => $model_id])->first();

        $value = @$custom_field_value->value;

        if(!$value){
            $value = CustomFieldValue::query()->selectRaw('MAX(CAST(IFNULL(VALUE, 0) AS UNSIGNED))  as value') 
            ->where('custom_field_id', $custom_field_id)
            ->whereHas('custom_field', function ($q) use ($module){
                $q->where('model_type', $module);
            })
            ->first()->value + 1;
        }

        $padding = CustomField::find($custom_field_id)->padding;
        
        return   $padding ? str_pad($value, $padding, '0', STR_PAD_LEFT) : $value;

    }

    public static function appendCustomModuleRules($module, &$rules, &$comments = [])
    {
        $custom_fields = self::getCustomfieldsByModule($module);

        foreach ($custom_fields as $custom_field) {
            if($custom_field->field_type == 'increment'){
                continue;
            }

            $rules[$custom_field->name][] = $custom_field->required ? 'required' : 'nullable';

            if($custom_field->field_type == 'date'){
                $rules[$custom_field->name][] = 'date';
            }

            if($custom_field->field_type == 'time'){
                $rules[$custom_field->name][] = 'date_format:'.self::timeFormat;
            }

            if($custom_field->field_type == 'datetime'){
                $rules[$custom_field->name][] = 'date_format:'.self::datetimeFormat;
            }

            if($custom_field->field_type != 'textarea'){
                $rules[$custom_field->name][] = 'max:255';
            }
        }
    }

    public static function findObject($name, $value)
    {
        $cfield = CustomField::where('name', $name)->first();

        if($cfield){
            $cfvalue = $cfield->custom_field_values()->where('value', $value)->first();

            if($cfvalue){
                return $cfield->model_type::find($cfvalue->model_id);
            }
        }

        return null;
    }
}
