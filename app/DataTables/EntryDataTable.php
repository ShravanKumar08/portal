<?php

namespace App\DataTables;

use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class EntryDataTable extends BaseDataTable
{

    public $role = "admin";

    public $entry_items;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('employee.name', function ($model) {
                return $this->employeeLink($model->employee);
            })
            ->editColumn('date', function ($model) {
                return Carbon::parse($model->date)->format('d-m-Y');
            })
            ->editColumn('start', function ($model) {
                return $model->start && $model->start != '00:00:00' ? Carbon::parse($model->start)->format('g:i A') : '-';
            })
            ->editColumn('end', function ($model) {
                return $model->end && $model->end != '00:00:00' ? Carbon::parse($model->end)->format('g:i A') : '-';
            })
            ->addColumn('break_hours', function ($model) {
                $this->entry_items = $model->getEntryItems();
                return $model->total_out_hours;
            })
            ->addColumn('worked_hours', function ($model) {
                return $model->total_in_hours;
            })
            ->addColumn('total_hours', function ($model) {
                return $model->total_hours;
            })
            ->editColumn('status', function ($model) {
                if ($model->status == "A") {
                    $color_class = 'success';
                } elseif ($model->status == "P") {
                    $color_class = 'danger';
                }else{
                    $color_class = 'inverse';
                }

                if ($this->role == "admin") {
                    $show_end_time = \Carbon\Carbon::now()->toDateString() == $model->date ? 0 : 1;

                    return "<div class='btn-group ".$this->hasPermissionAccess('entry.addremarks')." '>
                                <button type='button' class='btn btn-{$color_class} btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>{$model->statusname}</button>
                                <div class='dropdown-menu animated lightSpeedIn'>
                                    <li><a href='#' data-id='$model->id' data-status='A' data-showendtime ='$show_end_time' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Approve</a></li>
                                    <li><a href='#' data-id='$model->id' data-status='D' data-showendtime ='0' data-toggle='modal' data-target='#RemarksModal' class='dropdown-item btn-status'>Decline</a></li>
                                </div>
                            </div>";
                } else {
                    return "<span class='label label-{$color_class}'>{$model->statusname}</span>";
                }
            })
            ->addColumn('action', function ($model) {
                if ($this->role == 'admin') {
                    $action = '<a href="' . route('entry.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('entry.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                    $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('entry.show').'" title="View" data-url="' . url('admin/entry', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= " <button class='btn btn-success ".$this->hasPermissionAccess('entry.entryitems')." ' type='button' data-date='" . $model->date . "' data-id='" . $model->id . "' title='View Attendance' data-model='entry' data-toggle='modal' data-target='#AttendanceModal'><i class='fa fa-id-card'></i></button>";
//                            $action .= '<a href="' . route('entry.access', $model->id) . '" class="btn btn-success" title="Make Super admin"><i class="fa fa-user"></i></a>&nbsp;';
                    $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('entry.destroy').'" type="button" data-id="' . $model->id . '" data-model="entry" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..." title="Delete"><i class="fa fa-trash"></i></a>';
                    return $action;
                }

                if ($this->role == 'trainee') {
                    $action = '<button class="btn btn-default btn-view" title="View" data-url="' . url('trainee/entry', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= " <button class='btn btn-success' type='button'  data-date='" . $model->date . "' data-id='" . $model->id . "' title='View Attendance' data-model='entry' data-toggle='modal' data-target='#AttendanceModal'><i class='fa fa-id-card'></i></button>";
                } else {
                    $action = '<button class="btn btn-default btn-view" title="View" data-url="' . url('employee/entry', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                    $action .= " <button class='btn btn-success' type='button'  data-date='" . $model->date . "' data-id='" . $model->id . "' title='View Attendance' data-model='entry' data-toggle='modal' data-target='#AttendanceModal'><i class='fa fa-id-card'></i></button>";
                }

                return $action;
            })
            ->rawColumns(['status', 'action', 'employee.name', 'created_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Entry $model)
    {
        $query = $model->newQuery()->select(['entries.*'])->with(['employee']);
        if ($employee_id = request()->employee_id) {
            $query->whereIn('employee_id', $employee_id);
        }

        if (request()->from_date && request()->to_date) {
            $query->whereBetween('date', [request()->from_date, request()->to_date]);
        }

        if (!($this->role == "admin")) {
            $query->where('employee_id', '=', \Auth::user()->employee->id);
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
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {

        $params = $this->getBuilderParameters();
        if ($this->role == 'admin') {
            $params = $this->getBuilderParameters('entry.create');
            $params['order'] = [[1, 'desc']];
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
        $columns = [
            'date',
            'start' => ['title' => 'Start Time'],
            'end' => ['title' => 'End Time'],
            'worked_hours' => ['title' => 'Worked'],
            'break_hours' => ['title' => 'Break'],
            'total_hours' => ['title' => 'Total'],
            'status',
//            'inip' => ['title' => 'Time In IP'],
//            'outip' => ['title' => 'Time Out IP']
        ];
        if ($this->role == 'admin') {
            $columns = [
                'employee.name' => ['title' => 'Employee Name'],
                'date',
                'start' => ['title' => 'Start Time'],
                'end' => ['title' => 'End Time'],
                'worked_hours' => ['title' => 'Worked'],
                'break_hours' => ['title' => 'Break'],
                'total_hours' => ['title' => 'Total'],
                'created_at'=> ['visible' => false]
            ];
            if(\App\Helpers\SecurityHelper::hasAccess('entry.addremarks')){
                    $columns[] = 'status';
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
        return 'Entry' . date('YmdHis');
    }

}
