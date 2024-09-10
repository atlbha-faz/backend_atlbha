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
        $data = Trip::get();
        if ($request->has('id')) {
            $data->where('package_id', $request->$id);
        }
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
            'package_id' => $request->package_id,
        ]);

        // return new CountryResource($country);
        $success['trip_details'] = new TripResource($trip);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة تفاصيل الباقة بنجاح', 'trip Added successfully');

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

    public function update(TripRequest $request, $id)
    {
        $trip = Trip::query()->find($id);
        if (is_null($trip)) {
            return $this->sendError("تفاصيل الباقة غير موجودة", "Trip is't exists");
        }
        $trip->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $request->image,
            'package_id' => $request->package_id

        ]);
        //$country->fill($request->post())->update();
        $success['trip_details'] = new TripResource($trip);
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

        $success['trip_details'] = new TripResource($trip);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف التفاصيل بنجاح', 'Trip deleted successfully');
    }
}
