<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\SeoResource;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $success['Seo'] = SeoResource::collection(Seo::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع تحسينات السيو بنجاح', 'Seo return successfully');
    }
    public function updateSeo(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'google_analytics' => 'nullable|url',
             'metatags' => 'nullable|string',
            'snappixel' => 'nullable|string',
            'tiktokpixel' => 'nullable|string',
            'twitterpixel' => 'nullable|string',
            'instapixel' => 'nullable|string',
            'key_words' => 'nullable',
            
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $image = null;
        if ($request->hasFile('og_image')) {
            $image = $request->file('og_image');
            $image->store('files/store', 'public');
            $image = $image->hashName();
        }
     
        $data = [
            "og_title" => $request->og_title,
            "og_type" => $request->og_type,
            "og_description" =>  $request->og_description,
            "og_image" => $image!=null ? asset('storage/files/store') . '/' . $image : $request->og_image,
            "og_url" => $request->og_url,
            "og_site_name" => $request->og_site_name,
        ];
        $seo = Seo::updateOrCreate([
            'store_id' => auth()->user()->store_id,
        ], [
            'google_analytics' => $request->google_analytics,
            // 'metatags' => $request->metatags,
            'snappixel' => $request->snappixel,
            'tiktokpixel' => $request->tiktokpixel,
            'twitterpixel' => $request->twitterpixel,
            'instapixel' => $request->instapixel,
            'key_words' => $request->key_words,
            'title' => $request->title,
            'metaDescription' => $request->metaDescription,
            'header' => $request->header,
            'footer' => $request->footer,
            'graph' =>json_encode($data),
            'tag' => $request->tag,
            'search' => $request->search,
        ]);

        $success['seos'] = new SeoResource($seo);
        $success['Seo'] = SeoResource::collection(Seo::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    }

}
