<?php

namespace App\DataTables;

use App\Helpers\AppHelper;
use App\Models\Officetimingslot;

class OfficetimingslotDataTable extends BaseDataTable
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
                $action = '<a href="' . route('officetimingslot.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('officetimingslot.edit').' " data-slotid="'.$model->id.'" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= '<a href="' . route('officetimingslot.create', "id=".$model->id) . '" class="btn btn-info '.$this->hasPermissionAccess('officetimingslot.create').' " title="Copy"><i class="fa fa-copy"></i></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('officetimingslot.show').' " title="View" data-url="' . url('admin/officetimingslot', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('officetimingslot.destroy').' " type="button" title="Delete" data-id="' . $model->id . '" data-model="officetimingslot" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
            })
             ->addColumn('employee_name', function ( $model) {
                 $names = collect(array_map(function ($name){
                     return explode(',', $name);
                 }, $model->officetimings()->pluck('employeeNames')->toArray()))->flatten()->toArray();

                 return AppHelper::insertStringAndImplode(implode(',', $names));

            })
            ->rawColumns([ 'action','employee_name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Officetimingslot $model
     * @return Builder2
     */
    public function query(Officetimingslot $model)
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
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction($this->getActionParamters())
            ->parameters($this->getBuilderParameters('officetimingslot.create'));
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
            'employee_name' => ['title' => 'Employee'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Officetimingslots' . date('YmdHis');
    }

}
