<?php

namespace App\DataTables;

use App\Models\Interviewstatus;

class InterviewStatusDataTable extends BaseDataTable
{

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
                        <li><a href='javascript: void(0)' data-id='".$model->id."' data-active='1' class='dropdown-item btn-status'>Active</a></li>
                        <li><a href='javascript: void(0)' data-id='".$model->id."' data-active='0' class='dropdown-item btn-status'>InActive</a></li>
                    </div>
                </div>";
            })
            ->addColumn('action', function ($model) {
                $action = '<a href="' . route('interviewstatus.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('interviewstatus.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('interviewstatus.show').'" title="View" data-url="' . url('admin/interviewstatus', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('interviewstatus.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="interviewstatus" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
            })
            
            ->rawColumns(['active', 'action']);
    }

    public function query(Interviewstatus $model)
    {   
        return  $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction($this->getActionParamters())
            ->parameters($this->getBuilderParameters('interviewstatus.create'));
    }

    protected function getColumns()
    {
        $columns = [
            'name'
        ];
        if(\App\Helpers\SecurityHelper::hasAccess('active')){
            $columns[] = 'active';
        }
        return $columns;
    }

    protected function filename()
    {
        return 'interviewstatus' . date('YmdHis');
    }

}
