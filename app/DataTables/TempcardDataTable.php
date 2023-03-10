<?php

namespace App\DataTables;

use App\Models\Tempcard;

class TempcardDataTable extends BaseDataTable
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
                $action = '<a href="' . route('tempcard.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('tempcard.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('tempcard.show').'" title="View" data-url="' . url('admin/tempcard', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('tempcard.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="tempcard" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
            })
            
            ->rawColumns(['active', 'action', 'tempcard','name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Tempcard $model)
    {
        return $model->newQuery()->with('employee')->select(['tempcards.*']);
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
            ->parameters($this->getBuilderParameters('tempcard.create'));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'employee.name','from','to','tempcard', 'active'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Tempcards' . date('YmdHis');
    }

}
