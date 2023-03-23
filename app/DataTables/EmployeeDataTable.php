<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\Employee;

class EmployeeDataTable extends BaseDataTable
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
            ->editColumn('name', function ($model) {
                return $this->employeeLink($model);
            })
            ->editColumn('user.deviceid', function ($model) {
                $model->appendCustomFields();
                return  $model->employee_deviceuserid;
            })
            ->editColumn('designation.name', function ($model) {
                return '<span class="label label-primary">' . $model->designation->name . '</span> ';
            })
            ->editColumn('user.active', function ($model) {
                if ($model->user->active == 1) {
                    $text = 'Active';
                    $btn_class = 'btn-success';
                } else {
                    $text = 'InActive';
                    $btn_class = 'btn-danger';
                }

                return "<div class='btn-group " . $this->hasPermissionAccess('active') . " '>
                                <button type='button' class='btn $btn_class btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$text</button>
                                <div class='dropdown-menu animated lightSpeedIn'>
                                    <li><a href='#' data-id='" . $model->user->id . "' data-active='1' class='dropdown-item btn-status'>Active</a></li>
                                    <li><a href='#' data-id='" . $model->user->id . "' data-active='0' class='dropdown-item btn-status'>InActive</a></li>
                                </div>
                            </div>";
            })
            ->addColumn('action', function ($model) {
                $action = '<div class="btn-group">
                                <button type="button" class="btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                </button>
                                <div class="dropdown-menu">
                                    <a href="' . route('employee.edit', $model->id) . '" class="dropdown-item ' . $this->hasPermissionAccess('employee.edit') . ' " title="Edit"><i class="fa fa-edit"></i>&nbsp;Edit</a>
                                    <a href="' . route('employee.show', $model->id) . '" class="dropdown-item ' . $this->hasPermissionAccess('employee.show') . ' " title="Edit"><i class="fa fa-eye"></i>&nbsp;View</a>
                                    <a href="' . route('employee.access', $model->id) . '" class="dropdown-item ' . $this->hasPermissionAccess('employee.access') . ' " title="Make super admin"><i class="fa fa-user"></i>&nbsp;Make super admin</a>
                                    <a href="' . route('employee.idps',$model->id) . '" class="dropdown-item ' . $this->hasPermissionAccess('employee.idps') . ' " title="Individual Development Plan"><i class="fa fa-address-book"></i>&nbsp;IDP</a>
                                    <a href="' . route('impersonate', $model->user_id) . '" class="dropdown-item ' . $this->hasPermissionAccess('impersonate') . ' " title="Login as employee"><i class="fa fa-sign-in"></i>&nbsp;Login as employee</a>
                                    <button class="dropdown-item btn-delete ' . $this->hasPermissionAccess('employee.destroy') . '" type="button" title="Delete" data-id="' . $model->id . '" data-model="employee" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i>&nbsp;Delete</button>

                                </div>
                                </div>';
                return $action;
            })
            ->setRowClass(function ($model) {
                return $model->user->hasRole('super-user') ? 'super-user-color' : '';
            })
            ->filterColumn('user.deviceid', function($query, $keyword) {
                $custom_field = CustomField::query()->where('name', 'employee_deviceuserid')->first();

                if($custom_field){
                    $custom_field_value = CustomFieldValue::query()->where('custom_field_id', $custom_field->id)->where('value', 'like', "%{$keyword}%")->pluck('model_id')->toArray();

                    if($custom_field_value){
                        $query->whereIn('id', $custom_field_value);
                    }
                }
            })
            ->rawColumns(['name', 'action', 'designation.name', 'superuser', 'user.active']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Employee $model)
    {
        $Query = $model->newQuery()->select(['employees.*'])->with(['designation', 'user']);

        $Query->active(request()->status == 'inactive' ? 0 : 1);

        if ($scope = request()->scope) {
            $Query->$scope();
        }
        
        if ($designation_id = request()->designation_id) {
            $Query->where('designation_id', $designation_id);
        }

        if($employeetype = request()->employeetype){
            $Query->where('employeetype', $employeetype);
        }

        return $Query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params = $this->getBuilderParameters('employee.create');
        $params['order'] = [[0, 'asc']];
        $params['paging'] = false;
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
            'name',
            'user.email' => ['title' => 'Email'],
            'user.deviceid' => ['title' => 'Device ID'],
//            'phone',
            'designation.name' => ['title' => 'Designation'],
        ];
        if (\App\Helpers\SecurityHelper::hasAccess('active')) {
            $columns['user.active'] = ['title' => 'Active'];
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
        return 'Employees' . date('YmdHis');
    }

}
