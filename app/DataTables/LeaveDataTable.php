<?php

namespace App\DataTables;

use App\Helpers\AppHelper;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveDataTable extends BaseDataTable
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
                    $action .= '<div class="btn-group">
                                <button type="button" class="btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                </button>
                                <div class="dropdown-menu">
                                  <a href="' . route('leave.edit', $model->id) . '" class="dropdown-item ' . $this->hasPermissionAccess('leave.edit') . ' " title="Edit"><i class="fa fa-edit"></i>&nbsp;Edit</a>
                                  <button class="dropdown-item ' . $this->hasPermissionAccess('leave.show') . '" title="View" data-url="' . url('admin/leave', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i>&nbsp;View</button>
                                  <button class="dropdown-item btn-delete ' . $this->hasPermissionAccess('leave.destroy') . '" type="button" title="Delete" data-id="' . $model->id . '" data-model="leave" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i>&nbsp;Delete</button>
                                  <a href="#" data-toggle="modal" data-leave_id="' . $model->id . '" data-target="#AuditModal" class="dropdown-item ' . $this->hasPermissionAccess('leave.audits') . ' " title="Audits"><i class="fa fa-history"></i>&nbsp;Audits</a>';
                    if ($model->status == 'A') {
                        $action .= '<div class="dropdown-divider"></div>
                                  <a title="Toggle Paid/Casual/Compensation" href="" class="dropdown-item ' . $this->hasPermissionAccess('leave.toggleLeave') . '" data-toggle="modal" data-leave_id="' . $model->id . '" data-name="' . $model->employee->name . '" data-target="#ToggleModal"><i class="fa fa-retweet" aria-hidden="true"></i>&nbsp;Toggle Paid/Casual/Compensate</a>
                                  <a title="Convert Leave to Permission" href="" class="dropdown-item ' . $this->hasPermissionAccess('leave.convertLeave') . '" data-toggle="modal" data-leave_id="' . $model->id . '" data-target="#ConvertLeaveModal"><i class="fa fa-compress" aria-hidden="true"></i>&nbsp;Convert Leave to Permission</a>
                                    </div>
                                  </div>';
                    }
                } else {
                    $prefix = $this->role;
                    if ($model->status == 'P') {
                        $action .= '<a href="' . route("$prefix.leave.edit", $model->id) . '" class="btn btn-warning" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    }
                    $action .= '<button class="btn btn-default btn-view" title="View" data-url="' . url("$prefix/leave", $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                }

                return $action;
            })
            ->editColumn('status', function ($model) {
                $color_class = AppHelper::getButtonColorByStatus($model->status);

                if ($this->role == "admin" || \Auth::user()->hasRole('super-user')) {
                    return "<div class='btn-group " . $this->hasPermissionAccess('leave.addremarks') . " '>
                                <button type='button' class='btn btn-{$color_class} btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>{$model->statusname}</button>
                                <div class='dropdown-menu animated lightSpeedIn'>
                                    <li><a href='#' data-id='$model->id' data-status='A' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Approve</a></li>
                                        <li><a href='#' data-id='$model->id' data-status='D' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Decline</a></li>
                                </div>
                            </div>";
                } else {
                    return "<span class='label label-{$color_class}'>{$model->statusname}</span>";
                }
            })
            ->editColumn('employee.name', function ($model) {
                return $this->employeeLink($model->employee);
            })
            ->editColumn('start', function ($leave) {
                return $leave->leavedates;
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
            ->rawColumns(['status', 'action', 'toggle', 'employee.name', 'checkbox_select', 'reason', 'remarks', 'created_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Employee $model
     * @return Builder2
     */
    public function query(Leave $model)
    {
        $query = $model->newQuery()->select(['leaves.*'])->with(['employee']);
        
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
            $query->whereHas('leaveitems', function ($q) {
                $q->whereBetween('date', [request()->from_date, request()->to_date]);
            });
        }

        $query->whereHas('employee', function ($q) {
            if(request()->has('inactive_employee') == false) {
                $q->active();
            }

            if(request()->employeetype == 'P'){
                $q->where('employeetype', 'P');
            }
        });

        if($this->role == 'trainee' || request()->employeetype == 'T'){
            $query->withoutGlobalScope('permanent');
            $query->trainee();
        }

        if (Auth::user()->hasRole('super-user')) {
            $team = @Auth::user()->employee->team;
            $teamMembers = $team->teamMembers->pluck('teammate_id')->toArray();

            if ($team) {
                $query->whereHas('employee', function ($q) use ($teamMembers) {
                    return $q->whereIn('id', $teamMembers);
                });
            } else {
                $query = $query->where('id', 'SuperUserHaveNotTeam');
            }
            
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
            $buttons = [
                'create',
                'export',
                'print',
                'reset',
                'reload',
                [
                    'extend' => 'collection',
                    'text' => '<i class="fa fa-edit"></i> Bulk update',
                    'autoClose' => true,
                    'buttons' => [
                        [
                            'extend' => 'bulk_approve',
                            'className' => 'dropdown-item btn-status',
                            'init' => 'function(api, node, config) {
                                $(node).removeClass("dt-button btn-default")
                            }'
                        ],
                        [
                            'extend' => 'bulk_decline',
                            'className' => 'dropdown-item btn-status',
                            'init' => 'function(api, node, config) {
                                $(node).removeClass("dt-button btn-default")
                        }'
                        ]
                    ],
                ],'colvis'
            ];

            if (!(\App\Helpers\SecurityHelper::hasAccess('leave.create'))) {
                unset($buttons[0]);
            }

            if (!(\App\Helpers\SecurityHelper::hasAccess('leave.bulkchangestatus'))) {
                unset($buttons[5]);
            }

            $params['order'] = [[6, 'desc']];
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
                    'exportable' => false,
                ],
                'employee.name' => ['title' => 'Name'],
                'days',
                'start' => ['title' => 'Date'],
//                'compensatedays' => ['title' => 'Compensate'],
                'reason',
                'remarks',
//                'toggle' => ['title' => 'Toggle <br />Paid/Casual'],
                'created_at'=> ['visible' => false],
                'status',
            ];
            // if (\App\Helpers\SecurityHelper::hasAccess('leave.addremarks')) {
            //     $columns[] = 'status';
            // }
        } else {
            $columns = [
                'start' => ['title' => 'Date'],
                'days',
                'reason',
                'status',
//                'compensatedays' => ['title' => 'Compensate'],
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
        return 'Leaves' . date('YmdHis');
    }

}
