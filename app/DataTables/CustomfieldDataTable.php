<?php

namespace App\DataTables;

use App\Models\CustomField;

class CustomfieldDataTable extends BaseDataTable
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
            ->editColumn('required', function ($model){
                return $model->required ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-close text-danger"></i>';
            })
            ->addColumn('action', function ($model) {
                $action = '<a href="' . route('customfield.edit', $model->id) . '" class="btn btn-warning '.$this->hasPermissionAccess('customfield.edit').' " title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                $action .= ' <button class="btn btn-danger btn-delete '.$this->hasPermissionAccess('customfield.destroy').' " type="button" title="Delete" data-id="' . $model->id . '" data-model="customfield" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..."><i class="fa fa-trash"></i></a>';
                return $action;
            })
            ->rawColumns(['action', 'required']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Employee $model
     * @return Builder2
     */
    public function query(CustomField $model)
    {
        if (@request()->formgroup) {
            return $model->where('formgroup', request()->formgroup)->newQuery();
        } else {
            return $model->newQuery();
        }
        
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction($this->getActionParamters())
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'label',
            'name',
            'required',
            'field_type'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Customfields' . date('YmdHis');
    }

}
