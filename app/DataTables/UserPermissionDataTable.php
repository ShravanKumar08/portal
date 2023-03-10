<?php

namespace App\DataTables;

use App\Helpers\AppHelper;
use App\Models\Userpermission;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;

class UserPermissionDataTable extends BaseDataTable
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
                $action = '';

                if ($this->role == "admin") {
                    $action .= '<a href="' . route('userpermission.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('userpermission.edit').'" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('userpermission.show').'" title="View" data-url="' . url('admin/userpermission', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= '<a href="#" data-toggle="modal" data-permission_id="' . $model->id . '" data-target="#AuditModal" class="btn btn-info  '.$this->hasPermissionAccess('userpermission.audits').'" title="Audits"><i class="fa fa-history"></i></a>&nbsp;';
                    $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('userpermission.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="userpermission" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                } else {
                    $prefix = $this->role;
                    if ($model->status == 'P') {
                        $action .= '<a href="' . route("$prefix.userpermission.edit", $model->id) . '" class="btn btn-warning" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    }
                    $action .= '<button class="btn btn-default btn-view" title="View" data-url="' . url("$prefix/userpermission", $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                }
                return $action;
            })
            ->editColumn('status', function ($model) {
                $color_class = AppHelper::getButtonColorByStatus($model->status);

                if(\Auth::user()->hasRole('super-user')){
                    return "<span class='label label-{$color_class}'>{$model->statusname}</span>";
                 } else if ($this->role == "admin") {
                    $time = Carbon::parse($model->end)->format('H:i');
                    return "<div class='btn-group ".$this->hasPermissionAccess('userpermission.addremarks')."'>
                                <button type='button' class='btn btn-{$color_class} btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>{$model->statusname}</button>
                                <div class='dropdown-menu animated lightSpeedIn'>
                                    <li><a href='#' data-id='$model->id' data-status='A' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Approve</a></li>
                                    <li><a href='#' data-id='$model->id' data-status='D' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Decline & Extended Hours</a></li>
                                    <li><a href='#' data-id='$model->id' data-date='$model->date' data-time='$time' data-status='U' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Decline</a></li>
                                </div>
                            </div>";
                } else {
                    return "<span class='label label-{$color_class}'>{$model->statusname}</span>";
                }
            })
            ->editColumn('start', function ($model) {
                return Carbon::parse($model->start)->format('h:i A').' - '.Carbon::parse($model->end)->format('h:i A');;
            })
            ->editColumn('employee.name', function ($model) {
                return $this->employeeLink($model->employee);
            })
            ->editColumn('date', function ($model) {
                return Carbon::parse($model->date)->format('d-m-Y');
            })
            ->addColumn('checkbox_select', function ($model) {
                return '<input type="checkbox" id="inputBook-' . $model->id . '" name="checkbox_select[' . $model->id . ']" value="' . $model->id . '" class="checkrow" /><label for="inputBook-' . $model->id . '"></label>';
            })
            ->editColumn('reason', function ($model) {
                return AppHelper::insertBreak($model->reason);
            })
            ->editColumn('remarks', function ($model) {
                return AppHelper::insertBreak($model->remarks);
            })
            ->rawColumns(['status', 'action', 'employee.name', 'checkbox_select', 'reason', 'remarks', 'created_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Userpermission $model)
    {
        $query = $model->newQuery()->select(['userpermissions.*'])->with(['employee']);

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

            if(request()->employeetype == 'P'){
                $q->where('employeetype', 'P');
            }
        });

        if($this->role == 'trainee' || request()->employeetype == 'T'){
            $query->trainee();
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
        $params = $this->getBuilderParameters();

        if ($this->role == 'admin') {
            $params['order'] = [[6, 'desc']];
            $buttons = ['create', 'export','print', 'reset', 'reload', [
                    'extend' => 'collection',
                    'text' => '<i class="fa fa-edit"></i>&nbsp;Bulk update',
                    'autoClose' => true,
                    'buttons' => ['bulk_approve', 'bulk_decline'],
                    'buttons' => [['extend' => 'bulk_approve', 'className' => 'dropdown-item btn-status', 'init' => 'function(api, node, config) {
                        $(node).removeClass("dt-button btn-default")
                        }'],
                        ['extend' => 'bulk_decline', 'className' => 'dropdown-item btn-status', 'init' => 'function(api, node, config) {
                        $(node).removeClass("dt-button btn-default")
                    }']
                    ],
                ]
            ];
            if(!(\App\Helpers\SecurityHelper::hasAccess('userpermission.create'))){
                unset($buttons[0]);
            }
            
            if(!(\App\Helpers\SecurityHelper::hasAccess('userpermission.bulkchangestatus'))){
                unset($buttons[5]);
            }
            
            $params['buttons'] = array_values($buttons);
        } else {
            $params['order'] = [[0, 'desc']];
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
                'checkbox_select' => [
                    'title' => '<input type="checkbox" name="select_all" value="1" id="select-all"><label for="select-all"></label>',
                    'searchable' => false,
                    'orderable' => false,
                ],
                'employee.name' => ['title' => 'Name'],
                'date',
                'start' => ['title' => 'Time'],
                'reason',
                'remarks',
                'created_at'=> ['visible' => false],
                'status'
                
            ];
            // if(\App\Helpers\SecurityHelper::hasAccess('userpermission.addremarks')){
            //         $columns[] = 'status';
            // }
        } else {
            $columns = [
                'date',
                'start' => ['title' => 'Time'],
                'reason',
                'remarks',
                'status',
            ];
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Userpermissions' . date('YmdHis');
    }

}
