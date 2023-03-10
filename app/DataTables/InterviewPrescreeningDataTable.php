<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\InterviewPrescreening;

class InterviewPrescreeningDataTable extends BaseDataTable
{

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    
    public function dataTable($query)
    {
        return datatables($query)

            ->addColumn('status', function ($model){
                $cust_id = CustomField::where('name', 'interviewprescreening_status')->first()->id;
                return $model->custom_field_val()->where('custom_field_id', $cust_id)->first()->value;
            })

            ->addColumn('action', function ($model) {
                $action = '<a href="' . route('interviewprescreening.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('interviewprescreening.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= '<button class="btn btn-default btn-view '.$this->hasPermissionAccess('interviewprescreening.show').'" title="View" data-url="' . url('admin/interviewprescreening', $model->id) . '" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('interviewprescreening.destroy').'" type="button" title="Delete" data-id="' . $model->id . '" data-model="interviewprescreening" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
            })

            ->filterColumn('status', function($query, $keyword) {
                $custom_field = CustomField::query()->where('name', 'interviewprescreening_status')->first();

                if($custom_field){
                    $custom_field_value = CustomFieldValue::query()->where('custom_field_id', $custom_field->id)->where('value', 'like', "%{$keyword}%")->pluck('model_id')->toArray();

                    if($custom_field_value){
                        $query->whereIn('id', $custom_field_value);
                    }
                }
            })
            
            ->rawColumns(['action']);
    }
    
    /** 
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(InterviewPrescreening $model)
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
        $params = $this->getBuilderParameters('interviewprescreening.create');
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
        $columns = [
            'name'=>['title' => 'Name'],
            'email'=>['title' => 'Email'],
            'phone'=>['title' => 'Phone'],
            'status' => ['title' => 'Status'],
         ];
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'interviewprescreening' . date('YmdHis');
    }

}
