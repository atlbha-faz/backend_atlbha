<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController;
use App\Http\Requests\TripRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Trip::where('parent_id', null)->get();
       
        $success['trip_details'] = TripResource::collection($data);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع تفاصيل الباقة بنجاح', 'trip return successfully');
    }
    public function store(TripRequest $request)
    {
        $trip = Trip::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'parent_id' => $request->parent_id,

        ]);
        if ($request->has('data')) {
            foreach ($request->data as $data) {
                $sub_details = Trip::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'parent_id' => $trip->id,
                ]);
            }
        }

        // return new CountryResource($country);
        $success['trip_details'] = new TripResource($trip);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة تفاصيل الباقة بنجاح', 'trip Added successfully');

    }

    public function show($id)
    {
        $trip = Trip::query()->find($id);
        if (is_null($trip)) {
            return $this->sendError("الباقة غير موجودة", "Trip is't exists");
        }

        $success['trip_details'] = new TripResource($trip);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بنجاح', 'Trip showed successfully');
    }

    public function update(TripRequest $request, $id)
    {
        $main_trip = Trip::query()->find($id);
        if (is_null($main_trip)) {
            return $this->sendError("تفاصيل الباقة غير موجودة", "Trip is't exists");
        }
        $main_trip->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $request->image,
            'parent_id' => $request->parent_id,
       

        ]);
        if ($request->has('data')) {
            $trips = Trip::where('parent_id', $main_trip->id)->get();
            if ($trips) {
                foreach ($trips as $trip) {
                    $trip->delete();
                }
            }
            foreach ($request->data as $data) {
                $sub_details = Trip::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'parent_id' => $main_trip->id,
                ]);
            }
        }
        //$country->fill($request->post())->update();
        $success['trip_details'] = new TripResource($main_trip);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'Trip updated successfully');
    }
    public function destroy($id)
    {
        $trip = Trip::query()->find($id);
        if (is_null($trip)) {
            return $this->sendError("تفاصيل  الباقة غير موجودة", "Trip is't exists");
        }
        $trip->delete();
        $trips = Trip::where('parent_id', $trip->id)->get();
        if ($trips) {
            foreach ($trips as $trip) {
                $trip->delete();
            }
        }

        // $success['trip_details'] = new TripResource($trip);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف التفاصيل بنجاح', 'Trip deleted successfully');
    }
}
