<?php

namespace App\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class BaseDataTableScope implements DataTableScope
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        if (request()->has('row_offset') && request()->has('row_limit')) {
            $query->offset(request()->row_offset)->limit(request()->row_limit);
        }

        return $query;
    }
}
