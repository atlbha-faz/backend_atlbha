<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Seo;
use Illuminate\Http\Request;
use App\Http\Resources\SeoResource;
use App\Http\Requests\SeoAdminRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class SeoController extends BaseController
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
     * @param  \App\Models\Seo  $seo
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seo  $seo
     * @return \Illuminate\Http\Response
     */
    public function edit(Seo $seo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seo  $seo
     * @return \Illuminate\Http\Response
     */

    



    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seo  $seo
     * @return \Illuminate\Http\Response
     */
  
    public function index()
    {
        $success['Seo'] = SeoResource::collection(Seo::where('is_deleted', 0)->where('store_id', null)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع تحسينات السيو بنجاح', 'Seo return successfully');
    }
    public function updateSeo(SeoAdminRequest $request)
    {

        $seo = Seo::updateOrCreate([
            'store_id' => auth()->user()->store_id,
        ], [
            'google_analytics' => $request->google_analytics,
            'metatags' => $request->metatags,
            'snappixel' => $request->snappixel,
            'tiktokpixel' => $request->tiktokpixel,
            'twitterpixel' => $request->twitterpixel,
            'instapixel' => $request->instapixel,
            'key_words' => $request->key_words,

        ]);

        $success['seos'] = new SeoResource($seo);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    }
    
}
