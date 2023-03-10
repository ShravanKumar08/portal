<?php

namespace App\Http\Controllers;

use App\Helpers\CustomfieldHelper;
use App\Helpers\EntryHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tempcard;

class CamsunitController extends Controller
{
    public function attendance(Request $request)
    {
        try{
            $model = CustomfieldHelper::findObject(CustomfieldHelper::EMPLOYEE_DEVICE_FIELD, $request->userid);

            $att_time = Carbon::createFromTimestampUTC($request->att_time)->toDateTimeString();

            $date = Carbon::parse($att_time);

            //Check temp card exists for the user on that day
            if(!$model){
                $att_date = $date->toDateString();

                $tempcard = Tempcard::where('tempcard', $request->userid)->active()->where('from', '<=', $att_date)->where('to', '>=', $att_date)->first();

                if($tempcard){
                    $model = $tempcard->employee;
                }
            }

            if($model){
                $entryHelper = new EntryHelper($model, $date);
                $entryHelper->addAttendance($request);
            }
        }catch (\Exception $e){
            if(!config('app.debug')){
                app('sentry')->captureException($e);
            }
        }

        echo 'ok'; //Note: it should return a string only.
        exit;
    }
}
