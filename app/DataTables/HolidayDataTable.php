<?php

namespace App\DataTables;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class HolidayDataTable extends BaseDataTable
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
            ->editColumn('date', function ($model) {
                return Carbon::parse($model->date)->format('d-m-Y'). ' ('.Carbon::parse($model->date)->format('l').')';
            })
            ->addColumn('action', function ($model) {
                $action = '';

                if ($this->role == "admin") {
                    $action .= '<a href="' . route('holiday.edit', $model->id) . '" class="btn btn-warning ' . $this->hasPermissionAccess('holiday.edit') . ' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    $action .= '<button class="btn btn-default btn-view ' . $this->hasPermissionAccess('holiday.show') . '" title="View" data-url="' . url('admin/holiday', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= ' <button class="btn btn-danger btn-delete ' . $this->hasPermissionAccess('holiday.destroy') . '" type="button" title="Delete" data-id="' . $model->id . '" data-model="holiday" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';

                } else {
                    $action .= '<button class="btn btn-default btn-view" title="View" data-url="' . url('employee/holiday', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['date', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Holiday $model)
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
        // $params = $this->getBuilderParameters('holiday.create');


        $params['order'] = [[1, 'asc']];
        
        $params['paging'] = false;
        $params['retrieve'] = true;

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            'date',
            'action'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Holidays' . date('YmdHis');
    }

}
