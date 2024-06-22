<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\HomepageResource;
use App\Http\Requests\banarUpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\sliderUpdateRequest;
use App\Http\Controllers\api\BaseController as BaseController;

class HomepageController extends BaseController
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
        $success['Homepages'] = new HomepageResource(Homepage::where('store_id', null)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الصفحة الرئبسبة  بنجاح', 'Homepages return successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    public function edit(Homepage $homepage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */

    public function logoUpdate(Request $request)
    {
        $logohomepage = Homepage::where('store_id', null)->first();

        if (is_null($logohomepage) || $logohomepage->is_deleted != 0) {
            return $this->sendError("الصفحة غير موجودة", " homepage is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $logohomepage->updateOrCreate([
            'store_id' => null,
        ], [
            'logo' => $request->logo,
        ]);

        $success['homepages'] = new HomepageResource($logohomepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }

    public function banarUpdate(banarUpdateRequest $request)
    {
        $banarhomepage = Homepage::where('store_id', null)->first();
        if (is_null($banarhomepage) || $banarhomepage->is_deleted != 0) {
            return $this->sendError("الصفحة غير موجودة", " homepage is't exists");
        }
        $banarhomepage->update([
            'banarstatus1' => $request->banarstatus1,
            'banarstatus2' => $request->banarstatus2,
            'banarstatus3' => $request->banarstatus3,
            'banar3' => $request->banar3,
            'banar2' => $request->banar2,
            'banar1' => $request->banar1,
        ]);

        $success['banarhomepages'] = new HomepageResource($banarhomepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }

    public function sliderUpdate(sliderUpdateRequest $request)
    {
        $sliderhomepage = Homepage::where('store_id', null)->first();
        if (is_null($sliderhomepage) || $sliderhomepage->is_deleted != 0) {
            return $this->sendError("الصفحة غير موجودة", " homepage is't exists");
        }
        $sliderhomepage->update([
            'sliderstatus1' => $request->sliderstatus1,
            'sliderstatus2' => $request->sliderstatus2,
            'sliderstatus3' => $request->sliderstatus3,
            'slider1' => $request->slider1,
            'slider2' => $request->slider2,
            'slider3' => $request->slider3,
        ]);

        $success['sliderhomepages'] = new HomepageResource($sliderhomepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }

}
