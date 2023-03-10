<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;

class RemoveUnusedProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:fix:unusedproject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Unused Projects';

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
        $projects = Project::all();
        $bar = $this->output->createProgressBar(count($projects));
        $bar->start();

        foreach($projects as $project){
            $has_report = $project->reportitems()->count();
            if($has_report == 0){
                $project->delete();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info('Remove Successfully');
    }
}
