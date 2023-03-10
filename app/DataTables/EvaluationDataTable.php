<?php

namespace App\DataTables;

use App\Models\Evaluation;
use App\Models\Employee;


class EvaluationDataTable extends BaseDataTable
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
            ->addColumn('period', function($model) {
                return @$model->assessment->period;
            })
            ->addColumn('employee', function($model) {
                return @$model->assessment->employee->name;
            })
            ->editColumn('status', function ($model) {
                return '<label class="label label-'.($model->status ==  0 ? 'danger' : 'success').'">'.$model->statusname.'</label>';
            })
            ->addColumn('action', function ($model) {
                $action = '<a href="' . route('employee.evaluation.edit', $model->id) . '" class="btn btn-warning" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';

                return $action;
            })
            
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Evaluation $model)
    {
        $query = $model->newQuery()->with('assessment');

        if ($scope = request()->scope) {
            $query->$scope();
        }

        return $query;
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
            ->parameters($this->getBuilderParameters('evaluation.create'));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [];

        if(request()->scope == 'others') {
            $columns[] = 'employee';
        }
        
        $columns = array_merge($columns,[
            'period',
            'status',
        ]);

         return $columns;    
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Evaluations' . date('YmdHis');
    }

}
