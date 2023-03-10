<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Helpers\SecurityHelper;
use Illuminate\Support\Arr;
use Storage;
use View;

class BaseDataTable extends DataTable
{
    protected $excludeFromExport = ['action', 'checkbox_select'];

    public function getActionParamters()
    {
        return config('datatables-buttons.action_attributes');
    }

    protected function getBuilderParameters($create_route = null)
    {
        $params = config('datatables-buttons.parameters');
        
        if($create_route && SecurityHelper::hasAccess($create_route) == false){
            Arr::pull($params, 'buttons.0');
            $params['buttons'] = array_values($params['buttons']);
        }

        $params['drawCallback'] = 'function(settings){
                    var info = this.api().page.info();
                    export_rows_count = info.recordsDisplay;
                    export_progress_step = parseFloat(100/export_rows_count) * row_offset;
                }';

        return $params;
    }

    public function employeeLink($employee)
    {
        return '<a href="'.route('employee.show', $employee->id).'" target="_blank" data-impersonate-url="'.route('impersonate', $employee->user_id).'" title="'.$employee->name.'" class="rightclick">'
            . $employee->shortname . '</a>';
    }
    
    public function hasPermissionAccess($route)
    {
        return SecurityHelper::hasAccess($route) ? '' : 'hide';
    }

    public function render($view, $data = [], $mergeData = [])
    {
        if(request()->has('export_to_all')){
            $this->filename = request()->filename;

            if (request()->export_to_all == 'excel') {
                $this->exportToExcel();
            } else if (request()->export_to_all == 'pdf') {
                $this->pdf();
            }

            return response()->json(true);
        }

        return parent::render($view, $data, $mergeData);
    }

    public function exportToExcel(){
        $file_path = "tmp/{$this->getFilename()}";
        $row = intval($_GET['row_offset'] + 2);
        $export_data = $this->getDataForExport();

        //create or load
        if(!file_exists($file_path)){
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('Records');
            $objPHPExcel->setActiveSheetIndex(0);

            //create headers
            $header = array_keys($export_data[0]);
            $objPHPExcel->getActiveSheet()->fromArray($header, null, 'A1');
        }else{
            $objPHPExcel = \PHPExcel_IOFactory::load($file_path);
        }

        $sheet = $objPHPExcel->getActiveSheet();

        //create rows
        $sheet->fromArray($export_data, null, "A{$row}");
        $highestColumn = $sheet->getHighestDataColumn();

        //change incase hyperlink in a row
        $size = $row + sizeof($export_data);
        for($row; $row <= $size; $row++) {
            foreach (range('A', $highestColumn) as $column) {
                $cell = $sheet->getCell($column.$row);
                $cellValue = $cell->getValue();

                if($cellValue != strip_tags($cellValue)) {
                    try{
                        $tag = new \SimpleXMLElement($cellValue);

                        if($tag['href'] && filter_var($tag['href'], FILTER_VALIDATE_URL)) {
                            $value = $tag->__toString() ? $tag->__toString() : $tag['href'];
                            $cell->setValue(trim($value))
                                ->getHyperlink()
                                ->setUrl($tag['href']);
                        }
                    }catch (\Exception $exception){
                        $cell->setValue(trim(strip_tags($cellValue)));
                    }
                }
            }
        }

        // Save Excel 2007 file
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($file_path);
    }

    public function pdf()
    {
        $file_path = "tmp/{$this->request()->filename}";
        $data = $this->getDataForPrint();
        $flag = $this->request()->row_offset == 0;
        Storage::disk('public')->append($file_path, View::make('vendor.datatables.partials.row', compact('data', 'flag'))->render());
    }
}
