<?php

namespace App\DataTables;

use App\Models\Skill;

class SkillDataTable extends BaseDataTable
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
            ->editColumn('user.name', function ($model){
                return $model->user->name;
            })
            ->editColumn('skills', function ($model){
                $skills = json_decode($model->skills);
                $options = '';
                foreach ($skills as $skill) {
                    $options .= '<span class="badge badge-info" style="margin-left:5px;">'.$skill.'</span></h6>';
                }

                return $options;
            })
            ->rawColumns(['skills']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Team $model
     * @return Builder2
     */
    public function query(Skill $model)
    {
        return $model->with(['user'])->newQuery();
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
            ->minifiedAjax();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'user.name',
            'skills'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Skills' . date('YmdHis');
    }

}
