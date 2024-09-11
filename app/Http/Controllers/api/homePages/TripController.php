<?php
namespace App\Http\Controllers\api\homePages;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Requests\TripRequest;
use App\Http\Resources\TripResource;
use App\Http\Controllers\api\BaseController;

class TripController extends BaseController
{

        public function index(Request $request)
        {
            $data = Trip::get();
            if ($request->has('id')) {
                $data->where('package_id', $request->id);
            }
            $success['trip_details'] = TripResource::collection($data);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم ارجاع تفاصيل الباقة بنجاح', 'trip return successfully');
        }
    
   
    public function show($id)
    {
        $trip = Trip::query()->find($id);
        if (is_null($trip)) {
            return $this->sendError("الوحدة غير موجودة", "Trip is't exists");
        }

        $success['trip_details'] = new TripResource($trip);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بنجاح', 'Trip showed successfully');
    }
  
 
  
}
