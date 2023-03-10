<?php

namespace App\DataTables;

use App\Models\Lecture;
use App\Models\Entry;
use Carbon\Carbon;
use App\Helpers\AppHelper;
use App\Scopes\EmployeeScope;

class LectureDataTable extends BaseDataTable
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
        ->editColumn('status', function ($model) {
            $employee_id = @\Auth::user()->employee->id;
            $status = $model->employees()->where('employee_id',$employee_id)->pluck('status')->first();
            if($status == 'P' || $status == null){
                $color_class = 'info';
                $text = 'Pending';
            } else if($status == 'A'){
                $color_class = 'success';
                $text = 'Joined';
            } else {
                $color_class = 'danger';
                $text = 'Declined';
            }
            if($text != 'Not Eligible'){
                return "<div class='btn-group'>
                <button type='button' class='btn btn-{$color_class} btn-sm dropdown-toggle'  data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$text</button>
                <div class='dropdown-menu animated lightSpeedIn'>
                
                    <li><button data-id='$model->id'  data-status='A'  class='dropdown-item btn-status'>Join</button></li>
                    <li><button data-id='$model->id'  data-status='D'  class='dropdown-item btn-status'>Decline</button></li>
                </div>";
            } else {
                return "<div class='btn-group'>
                <button type='button' class='btn-info btn-sm '  data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$text</button>";
            }
        })
        ->editColumn('start', function ($model) {
            return Carbon::parse($model->start)->format('H:i');
        })
        ->editColumn('end', function ($model) {
            return Carbon::parse($model->end)->format('H:i');
        })
        ->addColumn('action', function ($model) {
            if(@\Auth::user()->employee->employeetype == 'T'){
                $type = 'trainee';
            }else {
                $type = 'employee';
            }
            
            if (request()->scope == "Self") {
                $action = '<a href="' . route( $type.'.lectures.edit',$model->id) . '"><button class="btn btn-default btn-edit btn-primary" title="Edit" " ><i class="fa fa-pencil"></i></button></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view " title="View" data-url="' . route($type.'.lectures.show', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= '<button class="btn btn-default btn-view btn-info" title="View" data-url="' . route($type.'.lectures.list', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="ti-menu"></i></button>&nbsp;';     
                $action .= ' <button id="delete" class="btn btn-danger   type="button" title="Delete" data-id="' . $model->id . '" data-model="report" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';          
                return $action;
            } else if(request()->scope == "Others"){
                $action = '<button class="btn btn-default btn-view" title="View" data-url="' . route($type.'.lectures.show', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= '<button class="btn btn-default btn-view btn-info" title="View" data-url="' . route($type.'.lectures.list', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="ti-menu"></i></button>&nbsp;';     
                return $action;
            } else {
                $action = '<a href="' . route('lectures.edit',$model->id) . '"><button class="btn btn-default btn-edit btn-primary" title="Edit" " ><i class="fa fa-pencil"></i></button></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view" title="View" data-url="' . route('lectures.show', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= '<button class="btn btn-default btn-viewP btn-info" title="View" data-url="' . route('lectures.list', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="ti-menu"></i></button>&nbsp;'; 
                $action .= ' <button id="delete" class="btn btn-danger   type="button" title="Delete" data-id="' . $model->id . '" data-model="report" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';              
                return $action;
            }
        })
        ->rawColumns(['employee.name','status','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Lecture $model
     * @return Builder2
     */
    public function query(Lecture $model)
    {
        $model = $model->withoutGlobalScope(EmployeeScope::class);
        $query = $model->newQuery()->select(['lectures.*'])->with(['employee','employees']);
        
        if ($scope = request()->scope) {
            $query->$scope();
        }

        if ($employee_id = request()->employee_id) {
            $query->whereIn('employee_id', $employee_id);
        }

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
        $params['order'] = [[0, 'desc']];
        $params = $this->getBuilderParameters();
        if (request()->scope == "Others") {
        $params['buttons'] = ['export','print','reset','reload','colvis'];
        }
        else{
            $params['buttons'] = ['create','export','print','reset','reload','colvis'];
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
        if (request()->scope == 'Self') {
            $columns = ['created_at'=> ['visible' => false], 'title','date','start','end'];
        } else if(request()->scope == 'Others'){
            $columns = ['created_at'=> ['visible' => false], 'employee.name' => ['title' => 'Name'],'title','date','start','end','status'];
        }else {
            $columns = ['created_at'=> ['visible' => false], 'employee.name' => ['title' => 'Name'],'title','date','start','end'];
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
        return 'Lectures' . date('YmdHis');
    }
}
