<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\RegionrResource;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['regions'] = RegionrResource::collection(Region::where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المناطق بنجاح', 'regions return successfully');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $region = Region::create([
            'name' => $request->name,

            'country_id' => $request->country_id,
        ]);

        $success['regions'] = new RegionrResource($region);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة منطقة بنجاح', 'Region Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Region  $c
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Region  $c
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $c
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $region)
    {
        $region = Region::query()->find($region);
        if (is_null($region) || $region->is_deleted != 0) {
            return $this->sendError("المنطقه غير موجودة", "region is't exists");
        }
        $input = $request->all();
        $validator = \Illuminate\Support\Facades\Validator::make($input, [
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $region->update([
            'name' => $request->input('name'),

            'country_id' => $request->input('country_id'),
        ]);

        $success['regions'] = new RegionrResource($region);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'region updated successfully');
    }
    public function changeStatus($id)
    {
        $region = Region::query()->find($id);
        if (is_null($region) || $region->is_deleted != 0) {
            return $this->sendError("المنطقه غير موجودة", "city is't exists");
        }

        if ($region->status === 'active') {
            $region->update(['status' => 'not_active']);
        } else {
            $region->update(['status' => 'active']);
        }
        $success['regions'] = new RegionrResource($region);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المنطقة بنجاح', 'region updated successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region  $c
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        if (is_null($region) || $region->is_deleted != 0) {
            return $this->sendError("المنطقه غير موجودة", "region is't exists");
        }
        $region->update(['is_deleted' => $region->id]);

        $success['regions'] = new CityResource($region);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المنطقه بنجاح', 'region deleted successfully');
    }
}
