<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UnitResource;
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
       $success['units']=UnitResource::collection(Unit::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الوحدات بنجاح','units return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show($unit)
     {
          $unit = Unit::query()->find($unit);
         if (is_null($unit) || $unit->is_deleted == 1){
         return $this->sendError("الوحدة غير موجودة","unit is't exists");
         }


        $success['$units']=New UnitResource($unit);
        $success['status']= 200;

         return $this->sendResponse($success,'تم عرض بنجاح','unit showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */

}
