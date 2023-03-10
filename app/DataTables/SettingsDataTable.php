<?php

namespace App\DataTables;

use App\Models\Setting;

class SettingsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('name', function ($model){
                return $model->settingName;
            })
            ->addColumn('action', function ($model){
                return '<a href="'.route('setting.edit', $model->id).'" title="Edit" class="btn btn-primary '.$this->hasPermissionAccess('setting.edit').'">Edit</a>';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Setting $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Setting $model)
    {
        $query = $model->newQuery();
        
        $query->emailtemplate(request()->scope == 'emailtemplate' ? 1 : 0);
        
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params = $this->getBuilderParameters();
        if(request()->scope != 'emailtemplate') {
            $params['buttons'] = array_values($params['buttons']);
        }        

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
            'name',
            'hint',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Settings_' . date('YmdHis');
    }
}
