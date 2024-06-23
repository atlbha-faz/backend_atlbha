<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class UnitController extends BaseController
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
    public function index()
    {
        $success['units'] = UnitResource::collection(Unit::where('is_deleted', 0)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الوحدات بنجاح', 'units return successfully');
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
    public function store(UnitRequest $request)
    {
        $unit = unit::create([
            'title' => $request->title,
            'file' => $request->file,
            'course_id' => $request->course_id,

        ]);

        // return new CountryResource($country);
        $success['units'] = new UnitResource($unit);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة وحدة بنجاح', 'unit Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show($unit)
    {
        $unit = Unit::query()->find($unit);
        if (is_null($unit) || $unit->is_deleted != 0) {
            return $this->sendError("الوحدة غير موجودة", "unit is't exists");
        }

        $success['$units'] = new UnitResource($unit);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بنجاح', 'unit showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit = Unit::query()->find($unit);
        if (is_null($unit) || $unit->is_deleted != 0) {
            return $this->sendError("الوحدة غير موجودة", "unit is't exists");
        }
        $unit->update([
            'title' => $request->input('title'),
            'file' => $request->input('file'),
            'course_id' => $request->input('course_id'),

        ]);
        //$country->fill($request->post())->update();
        $success['units'] = new UnitResource($unit);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'unit updated successfully');
    }

    public function changeStatus($id)
    {
        $unit = Unit::query()->find($id);
        if (is_null($unit) || $unit->is_deleted != 0) {
            return $this->sendError("الوحدة غير موجودة", "unit is't exists");
        }

        if ($unit->status === 'active') {
            $unit->update(['status' => 'not_active']);
        } else {
            $unit->update(['status' => 'active']);
        }
        $success['$unit'] = new UnitResource($unit);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة الوحدة بنجاح', 'unit updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy($unit)
    {
        $unit = Unit::query()->find($unit);
        if (is_null($unit) || $unit->is_deleted != 0) {
            return $this->sendError("الوحدة غير موجودة", "unit is't exists");
        }
        $unit->update(['is_deleted' => $unit->id]);

        $success['unit'] = new UnitResource($unit);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الوحدة بنجاح', 'unit deleted successfully');
    }
}
