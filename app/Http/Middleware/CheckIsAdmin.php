<?php

namespace App\Http\Middleware;

use App\Helpers\SecurityHelper;
use Closure;
use function abort;

class CheckIsAdmin
{
    public function handle($request, Closure $next) {
        abort_if(SecurityHelper::hasAccess($request->route()->getName()) == false, 403, 'Access Denied');
        return $next($request);
    }
}