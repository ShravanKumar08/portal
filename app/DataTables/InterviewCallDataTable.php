<?php

namespace App\DataTables;

use App\Models\InterviewCall;

class InterviewCallDataTable extends BaseDataTable
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
            ->addColumn('InterviewCall Time', function($model){
                return $model->interview_round()->where('round', 1)->first()->datetime;
             })
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
                $action = '<a href="' . route('interviewcall.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('interviewcall.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= '<button onclick="location.href=' ."'". route('interviewcall.show', $model->id) ."'". '" class="btn btn-default btn-view".'.$this->hasPermissionAccess('interviewcall.show').'" title="Show"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('interviewcall.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="interviewcall" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
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
    public function query(InterviewCall $model)
    {   
        return  $model->with(['candidate','status','interview_round'])->select(['interview_calls.*'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        $params = $this->getBuilderParameters('interviewcall.create');
        $params['order'] = [[3, 'desc']];

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
        $columns = [
            'candidate.name'=>['title' => 'Name'],
//            'candidate.email'=>['title' => 'Email'],
            'candidate.mobile'=>['title' => 'Mobile'],
            'created_at',
            'InterviewCall Time'
         ];
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'interviewcall' . date('YmdHis');
    }

}
