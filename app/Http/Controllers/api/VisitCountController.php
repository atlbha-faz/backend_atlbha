<?php

namespace App\Http\Controllers\api;

use App\Mail\SendOfferCart;

// use Spatie\Analytics\Facades\Analytics;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\api\BaseController as BaseController;

class VisitCountController extends BaseController
{
    public function visit(){
        //retrieve visitors and page view data for the current day and the last seven days
$analyticsData = \Analytics::fetchVisitorsAndPageViews(Period::days(7));

//retrieve visitors and page views since the 6 months ago
$analyticsData1 = \Analytics::fetchVisitorsAndPageViews(Period::months(6));
$analyticsData3 = \Analytics::performQuery(
    Period::days(7),
    'ga:sessions',
    [
        'metrics' => 'ga:sessions',
        'dimensions' => 'ga:country,ga:city,ga:operatingSystem,ga:deviceCategory',
        'sort' => '-ga:sessions'
    ]
);

$rows = $analyticsData3->rows;

foreach ($rows as $row) {
    $country = $row[0];
    $city = $row[1];
    $platform = $row[2];
    $deviceCategory = $row[3];

    // Do something with the country, city, platform, and device type
}
 $success['analyticsData']=$analyticsData;
 $success['analyticsData1']=$analyticsData1;
 $success['analyticsData3']=$rows;

        $success['status']= 200;

         return $this->sendResponse($success,'تم العرض ','visit show successfully');

    }

    
}
