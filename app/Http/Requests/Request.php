<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Request as ParentRequest;

class Request extends ParentRequest
{
    public static function all()
    {
        return request()->all();
    }

    public static function get($option = null)
    {
        return $option ? request()->$option : request()->all();
    }
}
