<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entryitem extends Model {

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'entryitems';

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }
}
