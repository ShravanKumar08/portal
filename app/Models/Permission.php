<?php

namespace App\Models;

use App\Traits\Uuids;

class Permission extends \Spatie\Permission\Models\Permission
{
    use Uuids;

    public $incrementing = false;
}
