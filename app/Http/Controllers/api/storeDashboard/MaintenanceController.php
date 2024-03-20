<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\MaintenanceResource;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends BaseController
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
        $success['Maintenances'] = MaintenanceResource::collection(Maintenance::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع و ضع الصيانة بنجاح', 'Maintenances return successfully');
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

    public function updateMaintenance(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
            'message' => 'required',
            //    'store_id'=>'required|exists:stores,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $maintenance = Maintenance::updateOrCreate([
            'store_id' => auth()->user()->store_id,
        ], [

            'title' => $request->title,
            'message' => $request->message,
            'status' => $request->status,

        ]);

        if ($maintenance->status === 'active') {
            $success['maintenances'] = new MaintenanceResource($maintenance);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تفعيل وضع الصيانه بنجاح', ' maintenance updated successfully');
        } else {
            $success['maintenances'] = new MaintenanceResource($maintenance);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم الغاء وضع الصيانه بنجاح', ' maintenance updated successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(Maintenance $maintenance)
    {
        //
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
   


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */

}
