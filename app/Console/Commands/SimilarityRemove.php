<?php

namespace App\Console\Commands;

use App\Helpers\EntryHelper;
use App\Models\Project;
use App\Models\Report;
use App\Models\Technology;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Mail\ReportsNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class SimilarityRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:similarity:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Remove similar text";

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
//        $this->findSimilarity(Technology::class);
        $this->findSimilarity(Project::class);
    }

    protected function findSimilarity($model, $column = 'name')
    {
        $records = $model::pluck($column)->toArray();

        $similars = [];

        foreach ($records as $x){
            foreach ($records as $y){
                if($x != $y){
                    $flatten = array_flatten($similars);

                    if(!in_array($x, $flatten) || !in_array($y, $flatten)){

                        similar_text($x, $y, $percent);

                        if($percent > 70){
                            $similars[] = compact('x', 'y');
                        }
                    }
                }
            }
        }

        if($similars){
            $flatten = array_unique(array_flatten($similars));

            $this->info(count($similars).' similarity found in '.$model);

            $this->line(implode(', ', $flatten));

            if ($this->confirm('Do you wish to continue removing ?')) {
                foreach ($similars as $k => $similar) {
                    $choice = $this->choice(($k + 1).') Which one do you want to remove  ?', [$similar['x'], $similar['y'], 'skip']);

                    if($choice != 'skip'){
                        $model::withTrashed()->where($column, $choice)->delete();
                    }
                }
            }
        }
    }
}
