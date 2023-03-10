<?php

namespace App\DataTables;

use App\Models\Platform;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class PlatformDataTable extends BaseDataTable
{

    public $role = 'admin';

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', function ($model) {
                $action = '';

                if ($this->role == "admin") {
                    $action .= '<a href="' . route('platform.edit', $model->id) . '" class="btn btn-warning ' . $this->hasPermissionAccess('platform.edit') . ' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    $action .= '<button class="btn btn-default btn-view ' . $this->hasPermissionAccess('platform.show') . '" title="View" data-url="' . url('admin/platform', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= ' <button class="btn btn-danger btn-delete ' . $this->hasPermissionAccess('platform.destroy') . '" type="button" title="Delete" data-id="' . $model->id . '" data-model="platform" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';

                } 
                return $action;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Platform $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        $params = $this->getBuilderParameters('platform.create');

        if ($this->role != 'admin') {
            Arr::pull($params, 'buttons.0');
            Arr::pull($params, 'buttons.2');
        } else {
            Arr::pull($params, 'buttons.3');
        }

        $params['order'] = [[1, 'asc']];
        $params['buttons'] = array_values($params['buttons']);

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction($this->getActionParamters())
            ->parameters($params);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Platforms' . date('YmdHis');
    }

}
