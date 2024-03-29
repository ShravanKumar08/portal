<?php

namespace App\Models;

use App\Traits\Uuids;

class Role extends \Spatie\Permission\Models\Role
{
    use Uuids;

    public $incrementing = false;
}
