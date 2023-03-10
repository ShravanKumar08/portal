<?php

namespace App\DataTables;

use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class GradeDataTable extends BaseDataTable
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
        return datatables($query)->editColumn('level', function ($model) {
                return $model->level;
            })->addColumn('action', function ($model) {
                $action = '';
                if ($this->role == "admin") {
                    $action .= '<a href="' . route('grade.edit', $model->id) . '" class="btn btn-warning ' . $this->hasPermissionAccess('grade.edit') . ' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    $action .= '<button class="btn btn-default btn-view ' . $this->hasPermissionAccess('grade.show') . '" title="View" data-url="' . url('admin/grade', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= ' <button class="btn btn-danger btn-delete ' . $this->hasPermissionAccess('grade.destroy') . '" type="button" title="Delete" data-id="' . $model->id . '" data-model="grade" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';

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
    public function query(Grade $model)
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
        $params = $this->getBuilderParameters('grade.create');

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
            'level',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Grades' . date('YmdHis');
    }

}
