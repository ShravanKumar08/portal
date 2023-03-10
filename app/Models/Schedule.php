<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Contracts\Auditable;

class Schedule extends BaseModel implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'schedules';
    protected $casts = ['value' => 'array'];
    
    protected $fillable = [
        'id', 'key', 'model_id','model_type', 'value', 'schedule_date', 'column','is_executed'
    ];
    
    public function scheduletypes()
    {
        return $this->morphTo();
    }

    public function getIsAlldayAttribute()
    {
        return count(array_unique($this->value['slots'])) == 1;
    }
}
