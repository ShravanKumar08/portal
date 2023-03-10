<?php
/**
 * Created by PhpStorm.
 * User: sumanas
 * Date: 13/4/18
 * Time: 1:03 PM
 */

namespace App\Helpers;

use Inani\Larapoll\Helpers\PollWriter;


class PollWriterHelper extends PollWriter
{
    public function drawResultOption($result, $total)
    {
        $votes = $result['votes'];
        if($total == 0){
            $percent = 0;
        }else{
            $percent = round(($votes * 100) /($total));
        }
        echo "<div class='result-option-id'>
                <strong>{$result['option']->name}</strong><span class='pull-right'>{$percent}%</span>
                <div class='progress'>
                    <div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='100' style='width: {$percent}%'>
                        <span class='sr-only'>{$percent}% Complete</span>
                    </div>
                </div>
            </div>";
    }
}