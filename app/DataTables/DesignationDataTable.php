<?php

namespace App\DataTables;

use App\Models\Designation;

class DesignationDataTable extends BaseDataTable
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
            ->editColumn('active', function ($model) {
                if ($model->active == 1) {
                    $text = 'Active';
                    $btn_class = 'btn-success';
                } else {
                    $text = 'InActive';
                    $btn_class = 'btn-danger';
                }

                return "<div class='btn-group ".$this->hasPermissionAccess('active')."'>
                    <button type='button' class='btn $btn_class btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$text</button>
                    <div class='dropdown-menu animated lightSpeedIn'>
                        <li><a href='#' data-id='".$model->id."' data-active='1' class='dropdown-item btn-status'>Active</a></li>
                        <li><a href='#' data-id='".$model->id."' data-active='0' class='dropdown-item btn-status'>InActive</a></li>
                    </div>
                </div>";
            })
            ->addColumn('action', function ($model) {
                $action = '<a href="' . route('designation.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('designation.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('designation.show').'" title="View" data-url="' . url('admin/designation', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('designation.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="designation" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
            })
            
            ->rawColumns(['active', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Designation $model)
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
            ->parameters($this->getBuilderParameters('designation.create'));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            'name',
        ];
        if(\App\Helpers\SecurityHelper::hasAccess('active')){
            $columns[] = 'active';
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Designations' . date('YmdHis');
    }

}
