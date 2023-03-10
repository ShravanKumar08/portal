<?php

namespace App\DataTables;

use App\Helpers\AppHelper;
use App\Models\Schedule;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;

class ScheduleDataTable extends BaseDataTable
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
            ->editColumn('is_executed', function ($model){
                return $model->is_executed ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-close text-danger"></i>';
            })
            ->editColumn('schedule_date', function ($model){
                return Carbon::parse($model->schedule_date)->toDateString();
            })
            ->addColumn('action', function ($model) {
                $action = '<a href="' . route('schedule.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('schedule.edit').'" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('schedule.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="schedule" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
               
            })
            ->rawColumns(['action', 'is_executed' ,'schedule_date']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Schedule $model)
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
        $params = $this->getBuilderParameters();

        if ($this->role == 'admin') {
            $buttons = [[
                    'extend' => 'collection',   
                    'text' => 'Create',
                    'autoClose' => true,
                    'buttons' => [['extend' => 'official_permission_leave', 'className' => 'dropdown-item btn-status', 'init' => 'function(api, node, config) {
                        $(node).removeClass("dt-button btn-default")
                        }'],
                        ['extend' => 'trainee_to_permanent', 'className' => 'dropdown-item btn-status', 'init' => 'function(api, node, config) {
                        $(node).removeClass("dt-button btn-default")
                        }'],
                        ['extend' => 'office_timing_slot', 'className' => 'dropdown-item btn-status', 'init' => 'function(api, node, config) {
                            $(node).removeClass("dt-button btn-default")
                    }']
                ],
                ],
            ];
            $params['buttons'] = array_values($buttons);
        }

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
        return [
            'schedule_date',
            'key',
            'is_executed',
            'created_at',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'schedules' . date('YmdHis');
    }

}
