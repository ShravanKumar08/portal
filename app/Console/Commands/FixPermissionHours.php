<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReportItem;
use App\Models\Report;

class FixPermissionHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature ='custom:fix:add_permissionhours';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Split permission hours and add it in permission hours Field';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start_date = $this->ask('Enter Start Date');
        $end_date = $this->ask('Enter End Date');

        $reports = Report::query()->whereBetween('date', [$start_date, $end_date])->latest()->get();
        $bar = $this->output->createProgressBar(count($reports));

        $bar->start();
        foreach($reports as $report){
            ReportItem::updateReportHours($report);
            $bar->advance();
        };
        $bar->finish();

        $this->info('Permission Hours splited Successfully'); 
    }
}
