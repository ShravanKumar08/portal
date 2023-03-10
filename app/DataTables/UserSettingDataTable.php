<?php

namespace App\DataTables;

use App\Models\UserSettings;

class UserSettingDataTable extends BaseDataTable
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
                return $model->userSettingName;
            })
            ->addColumn('action', function ($model) {
                $action = '';
                $action .= '<a href="' . route('employee.usersettings.edit', $model->id) . '" class="btn btn-warning" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                
                return $action;
            })
            ->rawColumns(['name', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Setting $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserSettings $model)
    {
        return $model->newQuery()->whereNotIn('name', UserSettings::getInvisibleNames())->where('user_id', \Auth::user()->id);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params = $this->getBuilderParameters();
        unset($params['buttons'][0]); //removed create
        $params['buttons'] = array_values($params['buttons']);
            
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
            'value',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'userSettings' . date('YmdHis');
    }
}
