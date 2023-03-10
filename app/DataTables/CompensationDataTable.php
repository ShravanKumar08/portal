<?php

namespace App\DataTables;

use App\Models\Compensation;
use App\Models\Employee;
use Carbon\Carbon;
use App\Helpers\AppHelper;

class CompensationDataTable extends BaseDataTable
{

    public $role = "admin";

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
                return $model->type == "L" ? "Leave" : "Permission";
            })
            ->editColumn('employee.name', function ($model) {
                            return $this->employeeLink($model->employee);
                        })
            ->addColumn('status', function ($model) {
                if ($model->status == 'P') {
                    $color = 'warning';
                } elseif ($model->status == 'F') {
                    $color = 'success-green';
                } elseif ($model->status == 'PD') {
                    $color = 'primary';
                } elseif ($model->status == 'NC') {
                    $color = 'info';
                } elseif ($model->status == 'C') {
                    $color = 'info';
                } elseif ($model->status == 'L') {
                    $color = 'success-green';
                } else {
                    $color = 'danger';
                }
                return '<span class="label label-' . $color . '">' . $model->statusname . '</span>';
            })
            ->editColumn('date', function($model){
                    return Carbon::parse($model->date)->format('d-m-Y');
                    })
            ->editColumn('reason', function($model){
               return AppHelper::insertBreak($model->reason);
            })
            ->addColumn('leavedays', function($model){
               return AppHelper::insertBreak($model->leaveDays ?: '-');
            })
            ->addColumn('action', function ($model) {

                if ($this->role == "admin") {

                    $action = '<div class="btn-group">
                                    <button type="button" class="btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                    </button>';
                    if($model->is_paid == 0 && $model->status != 'F'){
                        $action .= "<div class='dropdown-menu'>
                        <a href='#' data-id='$model->id' data-is_paid='2' '.$this->hasPermissionAccess('compensation.addremarks').' class='dropdown-item' data-target='#RemarksModal' data-toggle='modal'>Convert To Paid</a>
                        <a href='#' data-id='$model->id' data-is_paid='3' '.$this->hasPermissionAccess('compensation.addremarks').' class='dropdown-item' data-target='#RemarksModal' data-toggle='modal'>Used as Leave</a>
                        <a href='#' data-id='$model->id' data-is_paid='-1' '.$this->hasPermissionAccess('compensation.addremarks').' class='dropdown-item' data-target='#RemarksModal' data-toggle='modal'>No Compensation</a>
                        <button class='dropdown-item btn-delete ' . $this->hasPermissionAccess('compensation.destroy') . '' type='button' title='Delete' data-id=".$model->id." data-model='compensation' data-loading-text='<i class=\'fa fa-spin fa-spinner\'></i><i class='fa fa-trash'></i>&nbsp;Delete</button>
                        </div>
                        </div>";
                    } else if($model->is_paid == 1){
                        $action .= "<div class='dropdown-menu'>
                                    <a href='#' data-id='$model->id' data-is_paid='2'  '.$this->hasPermissionAccess('compensation.addremarks').' class='dropdown-item' data-toggle='modal' data-target='#RemarksModal'>Unmark & Convert Paid</a>
                                    <a href='#' data-id='$model->id' data-is_paid='0' '.$this->hasPermissionAccess('compensation.addremarks').'  class='dropdown-item' data-toggle='modal' data-target='#RemarksModal'>Unmark Paid</a>
                                    <button class='dropdown-item btn-delete ' . $this->hasPermissionAccess('compensation.destroy') . '' type='button' title='Delete' data-id=".$model->id." data-model='compensation' data-loading-text='<i class=\'fa fa-spin fa-spinner\'></i><i class='fa fa-trash'></i>&nbsp;Delete</button>
                                    </div>
                                    </div>";
                    } else if($model->is_paid == 2) {
                        $action .= "<div class='dropdown-menu'>
                        <a href='#' data-id='$model->id' data-is_paid='1'   '.$this->hasPermissionAccess('compensation.addremarks').'  data-toggle='modal'  class='dropdown-item' data-target='#RemarksModal'>Mark as Paid</a>
                        <button class='dropdown-item btn-delete ' . $this->hasPermissionAccess('compensation.destroy') . '' type='button' title='Delete' data-id=".$model->id." data-model='compensation' data-loading-text='<i class=\'fa fa-spin fa-spinner\'></i><i class='fa fa-trash'></i>&nbsp;Delete</button>
                        </div>
                        </div>";
                    }else if($model->is_paid == 3) {
                        $action .= "<div class='dropdown-menu'>
                        <a href='#' data-id='$model->id' data-is_paid='0'   '.$this->hasPermissionAccess('compensation.addremarks').'  data-toggle='modal'  class='dropdown-item' data-target='#RemarksModal'>Unmark Leave</a>
                        <button class='dropdown-item btn-delete ' . $this->hasPermissionAccess('compensation.destroy') . '' type='button' title='Delete' data-id=".$model->id." data-model='compensation' data-loading-text='<i class=\'fa fa-spin fa-spinner\'></i><i class='fa fa-trash'></i>&nbsp;Delete</button>
                        </div>
                        </div>";
                    } else {
                        $action .= "<div class='dropdown-menu'>
                        <button class='dropdown-item btn-delete ' . $this->hasPermissionAccess('compensation.destroy') . '' type='button' title='Delete' data-id=".$model->id." data-model='compensation' data-loading-text='<i class=\'fa fa-spin fa-spinner\'></i><i class='fa fa-trash'></i>&nbsp;Delete</button>
                        </div>
                        </div>";
                    }
                  
                    return $action;
                }
            })
            ->rawColumns(['status', 'leavedays', 'type', 'employee.name', 'reason', 'created_at', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Employee $model
     * @return Builder2
     */
    public function query(Compensation $model)
    {
        $query = $model->newQuery()->select(['compensations.*'])->with(['employee']);
        
        if (request()->from_date && request()->to_date) {
            $query->whereBetween('date', [request()->from_date, request()->to_date]);
        }
        
        if ($employee_id = request()->employee_id) {
            $query->whereIn('employee_id', $employee_id);
        }

        if($status = request()->status){
            $query->status($status);
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
            $params['order'] = [[7, 'desc']];
        } else {
            unset($params['buttons'][0]); //removed create
            $params['buttons'] = array_values($params['buttons']);
            $params['order'] = [[0, 'desc']];
        }

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
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
                'date' => ['title' => 'Compensation<br>Date'],
                'days',
                'reason',
                'type',
                'status',
                'leavedays' => ['title' => 'Leave Days'],
                'created_at'=> ['visible' => false],
                'action',
            ];
        } else {
            $columns = [
                'date' => ['title' => 'Compensation Date'],
                'days',
                'reason',
                'type',
                'status',
                'leavedays' => ['title' => 'Leave Days']
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
        return 'Compensations' . date('YmdHis');
    }

}
