<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;

// use Spatie\Analytics\Facades\Analytics;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class VisitCountController extends BaseController
{
    public function visit(){
        //retrieve visitors and page view data for the current day and the last seven days
$analyticsData = \Analytics::fetchVisitorsAndPageViews(Period::days(7));

//retrieve visitors and page views since the 6 months ago
$analyticsData1 = \Analytics::fetchVisitorsAndPageViews(Period::months(6));
$analyticsData3 = \Analytics::performQuery(
    Period::months(12),
    'ga:sessions',
    [
        'metrics' => 'ga:sessions',
        'dimensions' => 'ga:pagePath,ga:sourceMedium,ga:country,ga:city,ga:operatingSystem,ga:deviceCategory',
        'sort' => '-ga:sessions'
    ]
);

$rows1 = $analyticsData3->rows;

foreach ($rows1 as $row) {
    $country = $row[0];
    $city = $row[1];
    $platform = $row[2];
    $deviceCategory = $row[3];

    // Do something with the country, city, platform, and device type
}

$data1 = \Analytics::performQuery(
    Period::months(12),
    'ga:users',
    [
        'dimensions' => 'ga:deviceCategory',
        'sort' => '-ga:users',
    ]
);

$rows = $data1->rows;

$totalUsers = array_sum(array_column($rows, 1));

$averages = [];

foreach ($rows as $row) {
    $deviceCategory = $row[0];
    $users = $row[1];

   if ($totalUsers !== 0) {
    $averageUsers = round(($users / $totalUsers) * 100, 2);
} else {
    $averageUsers = 0;
}

    $averages[] = [
        'deviceCategory' => $deviceCategory,
        'averageUsers' => $averageUsers,
    ];
}



 $success['analyticsData']=$analyticsData;
 $success['analyticsData1']=$analyticsData1;
 $success['analyticsData3']=$rows1;
 $success['averages']=$averages;

        $success['status']= 200;

         return $this->sendResponse($success,'تم العرض ','visit show successfully');

    }

     public function storeClientVisit(Request $request){
            $input = $request->all();

            $validator =  Validator::make($input ,[
            'startDate'=>'date',
            'endDate'=>'date',

               ]);
            if ($validator->fails())
            {
             return $this->sendError(null,$validator->errors());
           }
          if(is_null($request->startDate) || is_null($request->endDate))
          {
            $startDate = Carbon::now()->subYear();
            $endDate = Carbon::now();
            $analyticsData3 = \Analytics::performQuery(
            Period::create($startDate, $endDate),
             'ga:sessions',
             [
              'metrics' => 'ga:sessions',
              'dimensions' => 'ga:pagePath,ga:sourceMedium',
             'sort' => '-ga:sessions'
             ]
         );

          $rows1 = $analyticsData3->rows;

          foreach ($rows1 as $row)
     {

        $pagePath = $row[0];
        $sourceMedium = $row[1];

       // Do something with the country, city, platform, and device type
      }
          }
          else
          {
            // dd($request->startDate);
        $startDate=date_create($request->startDate);
        //  dd($startDate);
        $endDate=date_create($request->endDate);
        // dd($startDate);
        $analyticsData3 = \Analytics::performQuery(
       Period::create($startDate, $endDate),
      'ga:sessions',
    [
        'metrics' => 'ga:sessions',
        'dimensions' => 'ga:pagePath,ga:sourceMedium',
        'sort' => '-ga:sessions'
    ]
);

        $rows1 = $analyticsData3->rows;
        if(!is_null($rows1)){
        foreach ($rows1 as $row) {
        $pagePath = $row[0];
        $sourceMedium = $row[1];


        // Do something with the country, city, platform, and device type
      }
    }
          }
        $success['analyticsData3']=$rows1;
        $success['status']= 200;

         return $this->sendResponse($success,'تم العرض ','visit show successfully');


        }


}
