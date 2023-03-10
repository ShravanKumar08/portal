<?php

namespace App\DataTables;

use App\Models\Report;
use App\Models\Entry;
use Carbon\Carbon;
use App\Helpers\AppHelper;

class ReportDataTable extends BaseDataTable
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
            ->editColumn('start', function ($model) {
                return $model->start ? Carbon::parse($model->start)->format('g:i A') : '-';
            })
            ->editColumn('manual_request_time', function ($model) {
                return $model->manual_request_time ? Carbon::parse($model->manual_request_time)->format('g:i A') : '-';
            })
            ->addColumn('projects', function ($model) {
                return $model->reportitems ? AppHelper::insertBreak(AppHelper::insertStringAndImplode($model->projectNames)) : '';
            })
            ->editColumn('employee.name', function ($model) {
                return $this->employeeLink($model->employee);
            })
            ->addColumn('action', function ($model) {
                if ($this->role == "admin") {
                    if ((request()->scope != "releaselock")) {
                        $action = '<a href="' . route('report.edit', $model->id) . '" class="btn btn-warning ' . $this->hasPermissionAccess('report.edit') . '" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                        $action .= '<button class="btn btn-default btn-view ' . $this->hasPermissionAccess('report.show') . '" title="View" data-url="' . url('admin/report', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                        $action .= ' <button class="btn btn-danger btn-delete ' . $this->hasPermissionAccess('report.destroy') . '" type="button" title="Delete" data-id="' . $model->id . '" data-model="report" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                        return $action;
                    }
                } 

                if ($this->role == 'trainee') {
                    $action = ($model->status == "A" && $model->date != \Carbon\Carbon::now()->toDateString()) ? '<a href="' . route('trainee.report.edit', $model->id) . '" class="btn btn-warning" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : "";
                    $action .= '<button class="btn btn-default btn-view" title="View" data-url="' . url('trainee/report', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                } else {
                    $action = ($model->status == "A" && $model->date != \Carbon\Carbon::now()->toDateString()) ? '<a href="' . route('employee.report.edit', $model->id) . '" class="btn btn-warning" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : "";
                    $action .= '<button class="btn btn-default btn-view" title="View" data-url="' . url('employee/report', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                }

                return $action;

            })
            ->editColumn('status', function ($model) {
                if ($model->status == "D") {
                    $color_class = 'inverse';
                } elseif ($model->status == "R") {
                    $color_class = 'info';
                } elseif ($model->status == "A") {
                    $color_class = 'primary';
                } elseif ($model->status == "S") {
                    $color_class = 'success';
                } else {
                    $color_class = 'danger';
                }

                if ($this->role == "admin") {
                    if (\Carbon\Carbon::now()->toDateString() == $model->date) {
                        $status = 'R';
                        $show_end_time = 0;
                        $text = 'In-progress';
                    } else {
                        $status = 'A';
                        $show_end_time = 1;
                        $text = 'Approve';
                    }

                    $hasRelease = $model->hasReleaseRequest ? 1 : 0;

                    $statusname = $model->statusname;

                    if($model->status == 'P'){
                        $hasEntry = Entry::where('employee_id' , $model->employee_id)->where('date' , $model->date)->exists();
                        
                        if(!$hasEntry && $model->reportitems->count() == 0 && $model->reason == ''){
                            $statusname = 'No Report';
                            $color_class = 'warning';
                        }   
                    }

                    return "<div class='btn-group " . $this->hasPermissionAccess('report.addremarks') . "'>
                                <button type='button' class='btn btn-{$color_class} btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>{$statusname}</button>
                                <div class='dropdown-menu animated lightSpeedIn'>
                                    <li><a href='#' data-id='$model->id' data-name = '$model->employee' data-test='tttt' data-status='$status' data-showendtime ='$show_end_time' data-toggle='modal' data-target='#RemarksModal' data-haslock='$hasRelease' class='dropdown-item btn-status '>$text</a></li>
                                    <li><a href='#' data-id='$model->id' data-status='D' data-showendtime ='0' data-toggle='modal' data-target='#RemarksModal' id='remarks_id' class='dropdown-item btn-status'>Decline</a></li>
                                    <li><a href='#' data-id='$model->id' data-status='S' data-showendtime ='0' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Mark as sent</a></li>
                                </div>
                            </div>";
                } else {
                    return "<span class='label label-{$color_class}'>{$model->statusname}</span>";
                }
            })
            ->addColumn('release_request', function ($report) {
                return $report->hasReleaseRequest ? "<button type='button' class='btn btn-sm btn-warning " . $this->hasPermissionAccess('report.releaselockbreak') . " ' data-report_id = '" . $report->id . "' data-toggle='modal' data-target='#releaseModal'>Release Request</button>" : '';
            })
            ->editColumn('reason', function ($model) {
                return AppHelper::insertBreak($model->lock_reason);
            })
            ->addColumn('checkbox_select', function ($model) {
                return '<input type="checkbox" id="inputBook-' . $model->id . '" name="checkbox_select[' . $model->id . ']"  value="' . $model->id . '" class="checkrow" /><label for="inputBook-' . $model->id . '"></label>';
            })
            ->editColumn('date', function ($model) {
                return Carbon::parse($model->date)->format('d-m-Y');
            })
            ->editColumn('totalhours', function ($model) {
                return $model->totalhours ? Carbon::parse($model->totalhours)->format('H:i') : '-';
            })
            ->editColumn('breakhours', function ($model) {
                return $model->breakhours ? Carbon::parse($model->breakhours)->format('H:i') : '-';
            })
            ->editColumn('workedhours', function ($model) {
                return $model->workedhours ? Carbon::parse($model->workedhours)->format('H:i') : '-';
            })
            ->rawColumns(['checkbox_select','status', 'action', 'release_request', 'employee.name', 'projects', 'reason', 'created_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(Report $model)
    {
        $query = $model->newQuery()->select(['reports.*', \DB::raw('(CASE
                WHEN status = "R" THEN 0
                WHEN status = "P" THEN 1
                WHEN status = "A" THEN 2
                ELSE 3
            END) as status_number')])->with(['employee']);

        if ($scope = request()->scope) {
            $query->$scope();
        }
        if ($employee_id = request()->employee_id) {
            $query->whereIn('employee_id', $employee_id);
        }
        // if ($scope = request()->scope) {
        //     $query->$scope();
        // }
        if ($status = request()->status) {
            $query->where('status', $status);
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
      // dd($query->tosql));
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
            $params = $this->getBuilderParameters('report.create');
            $params['order'] = [[9, 'asc'], [2, 'desc']];

            if (request()->scope == "releaselock") {
                $params['order'] = [ [1, 'desc']];
                return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters($params);
            }
            
            $params['buttons'] = [
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
                            'extend' => 'bulk_inprogress',
                            'className' => 'dropdown-item btn-status',
                            'init' => 'function(api, node, config) {
                                $(node).removeClass("dt-button btn-default")
                            }'
                        ],
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
                        ],
                        [
                            'extend' => 'bulk_sent',
                            'className' => 'dropdown-item btn-status',
                            'init' => 'function(api, node, config) {
                                $(node).removeClass("dt-button btn-default")
                        }'
                        ]                
                    ],
                ],'colvis',
            ];

        } else {
            $params['order'] = [[0, 'desc']];
        }
        // $params['buttons'] = array_values($params['buttons']);
        
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
            $columns = $this->getAdminColumns();
        } else {
            $columns = [
                'date',
                'start',
                'projects' => [
                    'orderable' => false,
                ],
                'status',
//                'totalhours',
//                'breakhours',
                'workedhours' => [
                    'title' => 'Worked <br />Hours',
                ],
                'breakhours' => [
                    'title' => 'Break <br />Hours',
                ],
            ];
        }
        return $columns;
    }

    protected function getAdminColumns()
    {
        if (request()->scope == "releaselock") {
            $columns = [
                'employee.name' => ['title' => 'Name'],
                'date',
                'reason',
                'action' => ['visible' => false],
                'created_at' => ['visible' => false]
            ];
            if (\App\Helpers\SecurityHelper::hasAccess('report.releaselockbreak')) {
                $columns[] = 'release_request';
            }
        } else {
            $columns = [
                'checkbox_select' => [
                    'title' => '<input type="checkbox" name="select_all" value="1" id="select-all"><label for="select-all"></label>',
                    'searchable' => false,
                    'orderable' => false,
                    'exportable' => false,
                ],
                'employee.name' => ['title' => 'Name'],
                'date',
                'start',
                'manual_request_time' => ['title' => 'Request Time'],
                'projects' => [
                    'orderable' => false,
                ],
                'remarks'  => ['visible' => false],
                'created_at' => ['visible' => false],
//                'workedhours' => [
//                    'title' => 'Worked <br />Hours',
//                ],
            ];
            if (\App\Helpers\SecurityHelper::hasAccess('report.addremarks')) {
                $columns['status'] = 'status';
                $columns['status_number'] = ['visible' => false,'searchable' => false];
            }
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
        return 'Report' . date('YmdHis');
    }

}
