<?php

namespace App\DataTables;

use App\Helpers\AppHelper;
use App\Models\LateEntry;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class LateEntryDataTable extends BaseDataTable
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
            ->addColumn('action', function ($model) {
                if ($this->role == "admin") {
                    $action = '<a href="' . route('late_entries.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('late_entries.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('late_entries.show').'" title="View" data-url="' . url('admin/late_entries', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('late_entries.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="late_entries" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                } else {
                    $action = '<button class="btn btn-default btn-view" title="View" data-url="' . url('employee/late_entries', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                }
                return $action;
            })
            ->editColumn('status', function ($model) {
                $color_class = AppHelper::getButtonColorByStatus($model->status);
                            
                if ($this->role == "admin") {
                    return "<div class='btn-group ".$this->hasPermissionAccess('late_entries.addremarks')." '>
                                <button type='button' class='btn btn-{$color_class} btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>{$model->statusname}</button>
                                <div class='dropdown-menu animated lightSpeedIn'>
                                    <li><a href='#' data-id='$model->id' data-status='A' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Approve</a></li>
                                    <li><a href='#' data-id='$model->id' data-status='E' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Approve & Extend Hours</a></li>
                                    <li><a href='#' data-id='$model->id' data-status='D' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Decline & Extend Hours</a></li>
                                    <li><a href='#' data-id='$model->id' data-status='U' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Decline</a></li>
                                </div>
                            </div>";
                } else {
                    return "<span class='label label-{$color_class}'>{$model->statusname}</span>";
                }
            })
            ->editColumn('employee.name', function ($model) {
                            return $this->employeeLink($model->employee);
                        })
            ->editColumn('date', function($model){
                        return Carbon::parse($model->date)->format('d-m-Y');
                    })
            ->addColumn('elapsed_time', function($model){
                    return Carbon::parse($model->elapsed)->format('i:s');
                })
            ->addColumn('time', function($model){
                return Carbon::parse($model->date)->format('H:i A');
            })
            ->rawColumns(['status', 'action','employee.name','elapsed_time','time', 'created_at']);
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(LateEntry $model)
    {
        $query = $model->newQuery()->select(['late_entries.*'])->with(['employee']);

        if ($scope = request()->scope) {
            $query->$scope();
        }

        if ($employee_id = request()->employee_id) {
            $query->whereIn('employee_id', $employee_id);
        }

        if ($model = request()->status) {
            $query->where('status', $model);
        }

        if (request()->from_date && request()->to_date) {
            $query->whereBetween('date', [request()->from_date, request()->to_date]);
        }

        $query->whereHas('employee', function ($q) {
            if(request()->has('inactive_employee') == false){
                $q->active();
            }

            if($employeetype = request()->employeetype){
                $q->where('employeetype', $employeetype);
            }
        });
        
        return $query;
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
            $params = $this->getBuilderParameters('late_entries.create');
            $params['order'] = [[4, 'desc']];
        } else {
            Arr::pull($params, 'buttons.0');
            $params['order'] = [[0, 'desc']];
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
        if ($this->role == "admin") {
            $columns = [
                'employee.name' => ['title' => 'Name'],
                'date',
                'time' => ['title' => 'In Time'],
                'elapsed_time' => ['title' => 'Elapsed Time (mins)'],
                'created_at'=> ['visible' => false],
                'remarks'
            ];
             if(\App\Helpers\SecurityHelper::hasAccess('late_entries.addremarks')){
                    $columns[] = 'status';
                }
        } else {
            $columns = [
                'date',
                'time' => ['title' => 'In Time'],
                'elapsed_time' => ['title' => 'Elapsed Time (mins)'],
                'status',
            ];
        }

        return $columns;

//         return [
//            'date',
//             'employee.name',
//        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'LateEntries' . date('YmdHis');
    }

}
