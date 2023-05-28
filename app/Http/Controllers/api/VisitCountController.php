<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

// use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use App\Http\Controllers\api\BaseController as BaseController;

class VisitCountController extends BaseController
{
    public function visit(){
        //retrieve visitors and page view data for the current day and the last seven days
$analyticsData = \Analytics::fetchVisitorsAndPageViews(Period::days(7));

//retrieve visitors and page views since the 6 months ago
$analyticsData1 = \Analytics::fetchVisitorsAndPageViews(Period::months(6));
 $success['analyticsData']=$analyticsData;
 $success['analyticsData1']=$analyticsData1;

        $success['status']= 200;

         return $this->sendResponse($success,'تم العرض ','visit show successfully');

    }
}
