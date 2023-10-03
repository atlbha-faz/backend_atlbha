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
    // public function index()
    // {
    //     $success['Seo'] = SeoResource::collection(Seo::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم ارجاع الكلمات المفتاحية بنجاح', 'Seo return successfully');
    // }

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
    // public function store(Request $request)
    // {
    //     $input = $request->all();
    //     $validator =  Validator::make($input ,[
    //         'index_page_title'=>'required|string|max:255',
    //         'index_page_description'=>'required|string',
    //         'key_words' =>'required|array',
    //        'show_pages'=>'required|in:short_link,name_link',
    //        'link'=>'required|url',
    //        'robots'=>'required|string',
    //         // 'store_id'=>'required|exists:stores,id'
    //     ]);
    //     if ($validator->fails())
    //     {
    //         return $this->sendError(null,$validator->errors());
    //     }
    //     $seo = Seo::create([
    //         'index_page_title' => $request->index_page_title,
    //         'index_page_description' => $request->index_page_description,
    //          'key_words' =>implode(',', $request->key_words),
    //         'show_pages' => $request->show_pages,
    //         'link' => $request->link,
    //         'robots' => $request->robots,
    //         'store_id'=> auth()->user()->store_id,
    //       ]);

    //      $success['seos']=New SeoResource($seo);
    //     $success['status']= 200;

    //      return $this->sendResponse($success,'تم إضافةالكلمات المفتاحية بنجاح','Seo Added successfully');
    // }

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

    // public function updateSeo(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'index_page_title' => 'required|string|max:255',
    //         'index_page_description' => 'required|string',
    //         //    'show_pages'=>'required|in:short_link,name_link',
    //         'key_words' => 'required',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [
    //         'index_page_title' => $request->index_page_title,
    //         'index_page_description' => $request->index_page_description,
    //         'key_words' => $request->key_words,
    //         //    'show_pages' => $request->show_pages,

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');
    // }

    // public function updateLink(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //         'link' => 'required|url',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [

    //         'link' => $request->input('link'),

    //     ]);

    //     $success['seos'] = new seoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');
    // }

    // public function updateRobots(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //         'robots' => 'required|string',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [

    //         'robots' => $request->input('robots'),

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');
    // }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seo  $seo
     * @return \Illuminate\Http\Response
     */
    // public function destroy($seo)
    // {
    //     $seo =Seo::query()->find($seo);
    //     if (is_null($seo) || $seo->is_deleted !=0){
    //         return $this->sendError("االكلمات المفتاحية غير موجودة","seo is't exists");
    //         }
    //        $seo->update(['is_deleted' => 1]);

    //        $success['seos']=New SeoResource($seo);
    //        $success['status']= 200;

    //         return $this->sendResponse($success,'تم حذف الكلمات المفتاحية بنجاح','seo deleted successfully');
    // }
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
            // 'metatags' => 'nullable|string',
            'snappixel' => 'nullable|string',
            'tiktokpixel' => 'nullable|string',
            'twitterpixel' => 'nullable|string',
            'instapixel' => 'nullable|string',
            'key_words' => 'required',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
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

        ]);

        $success['seos'] = new SeoResource($seo);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    }
    // public function updateMetaTags(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //         'metatags' => 'nullable|file|mimes:txt',
    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }

    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [

    //         'metatags' => $request->metatags,

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    // }
    // public function updateSnapPixel(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //         'snappixel' => 'nullable|file|mimes:txt',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [

    //         'snappixel' => $request->snappixel,

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    // }
    // public function updateTiktokPixel(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //         'tiktokpixel' => 'nullable|file|mimes:txt',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [

    //         'tiktokpixel' => $request->tiktokpixel,

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    // }
    // public function updateTwitterpixel(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //         'twitterpixel' => 'nullable|file|mimes:txt',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [

    //         'twitterpixel' => $request->twitterpixel,

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    // }
    // public function updateInstapixel(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //         'instapixel' => 'nullable|file|mimes:txt',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [

    //         'instapixel' => $request->instapixel,

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    // }

    // public function updateKeyWords(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'key_words' => 'required',

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $seo = Seo::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [
    //         'key_words' => $request->key_words,

    //     ]);

    //     $success['seos'] = new SeoResource($seo);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'seo updated successfully');

    // }
}
