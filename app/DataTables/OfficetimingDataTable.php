<?php

namespace App\DataTables;

use App\Helpers\AppHelper;
use App\Models\Employee;
use App\Models\Officetiming;

class OfficetimingDataTable extends BaseDataTable
{

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
                $action = '<a href="' . route('officetiming.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('officetiming.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('officetiming.show').' " title="View" data-url="' . url('admin/officetiming', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('officetiming.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="officetiming" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
            })
            ->addColumn('employee_name', function ( $model) {
                return AppHelper::insertStringAndImplode($model->employeeNames);
            })
            ->rawColumns([ 'action', 'employee_name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Employee $model
     * @return Builder2
     */
    public function query(Officetiming $model)
    {
        return $model->where('employeetype',request()->scope);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        $buttons = [['extend' => 'create',
                'action' => 'function ( e, dt, node, config ) {
                     $("#TimingsModal").modal("show");
                }' ],
                ['extend' => 'export'],
                ['extend' => 'print'],
                ['extend' => 'reset'],
                ['extend' => 'reload'],
                ['extend' => 'colvis']];
        
        if(!(\App\Helpers\SecurityHelper::hasAccess('officetiming.create'))){
            unset($buttons[0]);
        }
        
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction($this->getActionParamters())
            ->parameters([
            'dom' => 'Bfrtip',
            'buttons' => array_values($buttons),
            'select' => true,
        ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name' => [
                'width' => '80px',
            ],
            'employee_name' => [
                'width' => '80px',
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Officetimings' . date('YmdHis');
    }

}
