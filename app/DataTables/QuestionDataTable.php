<?php

namespace App\DataTables;

use App\Models\Question;
use Carbon\Carbon;

class QuestionDataTable extends BaseDataTable
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
                ->editColumn('type', function ($model) {
                return ($model->type=="D") ? "Description" : "Objective";
                })
                ->addColumn('platforms', function ($model) {
                    return $model->platforms->implode('name',", ");
                })->addColumn('action', function ($model) {
                $action = '';

                if ($this->role == "admin") {
                    $action .= '<a href="' . route('question.edit', $model->id) . '" class="btn btn-warning ' . $this->hasPermissionAccess('question.edit') . ' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    $action .= '<button class="btn btn-default btn-view ' . $this->hasPermissionAccess('question.show') . '" title="View" data-url="' . url('admin/question', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= ' <button class="btn btn-danger btn-delete ' . $this->hasPermissionAccess('question.destroy') . '" type="button" title="Delete" data-id="' . $model->id . '" data-model="question" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';

                } 
                return $action;
            })
            ->rawColumns(['grade.name','platforms.name','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(question $model)
    {
        return $model->newQuery()->select(["questions.*"])->with(['grade']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        $params = $this->getBuilderParameters('question.create');
        $params['order'] = [[0, 'asc']];
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
            'name' => ['title'=>"Name"],
            'type' => ['title' => "Type"],
            'grade.name' => ['title' => "Grade"],
            'platforms'=>['title'=>'Platforms']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'questions' . date('YmdHis');
    }

}
