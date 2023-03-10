<?php

namespace App\Models;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends BaseModel implements Auditable
{

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'projects';

    use \OwenIt\Auditing\Auditable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'active'];

    public static function getProjectReportDataByEmployees($emp, $request) 
    {
        $month_year = $request->month_year ?: date('Y-m');
        $from_date = $request->from_date ?: date('Y-m-01');
        $to_date = $request->to_date ?: date('Y-m-d');
        $filtertype =  $request->filtertype;
        $sortby = $request->sortby;

        $query = "SELECT projects.name,projects.id, group_concat(reports.employee_id) as employee_id,reports.start,reports.end,reports.date, SUM(TIMESTAMPDIFF(MINUTE, reportitems.start, reportitems.end)) 
        AS elapsed_minutes, CONCAT(FLOOR(SUM(TIMESTAMPDIFF(MINUTE, reportitems.start, reportitems.end))/60),
        'h ',LPAD(MOD(SUM(TIMESTAMPDIFF(MINUTE, reportitems.start, reportitems.end)),60),2,'0'),'m') AS elapse
                FROM reports
                INNER JOIN reportitems ON reports.id =reportitems.report_id
                INNER JOIN projects ON projects.id =reportitems.project_id
                Where reportitems.project_id IS NOT NULL";

        if ($emp) {
            $query .= " AND employee_id  In ('" . $emp . "')";
        }
        
        if($filtertype == 'M'){
            $date_query = "AND (DATE_FORMAT(reports.date,'%Y-%m')) = '{$month_year}' ";
        }else{
            $date_query = "AND reports.date BETWEEN '{$from_date}' AND  '{$to_date}' ";
        }

        $query .= " AND reports.deleted_at IS NULL $date_query GROUP BY projects.id";

        if ($sortby) {
            $query .= " ORDER BY $sortby ";
        } else {
            $query .= " ORDER BY projects.name ";
        }

        return \DB::select($query);
    }

    public function reportitems()
    {
        return $this->hasMany(ReportItem::class);
    }
}
