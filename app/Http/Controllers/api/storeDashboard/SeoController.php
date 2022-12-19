<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Seo;
use Illuminate\Http\Request;
use App\Http\Resources\SeoResource;
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
    public function index()
    {
        $success['Seo']=SeoResource::collection(Seo::where('is_deleted',0)->where('store_id',auth()->user()->store_id)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الكلمات المفتاحية بنجاح','Seo return successfully');
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
        $validator =  Validator::make($input ,[
            'index_page_title'=>'required|string|max:255',
            'index_page_description'=>'required|string',
            'key_words' =>'required|array',
           'show_pages'=>'required|in:short_link,name_link',
           'link'=>'required|url',
           'robots'=>'required|string',
            // 'store_id'=>'required|exists:stores,id'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $seo = Seo::create([
            'index_page_title' => $request->index_page_title,
            'index_page_description' => $request->index_page_description,
             'key_words' =>implode(',', $request->key_words),
            'show_pages' => $request->show_pages,
            'link' => $request->link,
            'robots' => $request->robots,
            'store_id'=> auth()->user()->store_id,
          ]);


         $success['seos']=New SeoResource($seo);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافةالكلمات المفتاحية بنجاح','Seo Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seo  $seo
     * @return \Illuminate\Http\Response
     */
    public function show($seo)
    {
        $seo= Seo::query()->find($seo);
        if (is_null($seo) || $seo->is_deleted==1){
               return $this->sendError("االكلمات المفتاحية غير موجودة","Seo is't exists");
               }
              $success['seos']=New SeoResource($seo);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض االكلمات المفتاحية بنجاح','Seo showed successfully');
    }
    public function changeStatus($id)
    {
        $seo = Seo::query()->find($id);
        if (is_null($seo) || $seo->is_deleted==1){
         return $this->sendError("الكلمات المفتاحية غير موجودة","seo is't exists");
         }
        if($seo->status === 'active'){
            $seo->update(['status' => 'not_active']);
     }
    else{
        $seo->update(['status' => 'active']);
    }
        $success['seo']=New seoResource($seo);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة الكلمات المفتاحية بنجاح',' seo status updared successfully');

    }
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
    public function update(Request $request, Seo $seo)
    {
        if (is_null($seo) || $seo->is_deleted==1){
            return $this->sendError("االكلمات المفتاحية غير موجودة"," seo is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
            'index_page_title'=>'required|string|max:255',
            'index_page_description'=>'required|string',
            'key_words' =>'required|array',
           'show_pages'=>'required|in:short_link,name_link',
           'link'=>'required|url',
           'robots'=>'required|string',
            // 'store_id'=>'required|exists:stores,id'

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $seo->update([
               'index_page_title' => $request->input('index_page_title'),
               'index_page_description' => $request->input('index_page_description'),
               'key_words' =>implode(',',$request->input('key_words')),
               'show_pages' => $request->input('show_pages'),
               'link' => $request->input('link'),
               'robots' => $request->input('robots'),
                // 'store_id' => $request->input('store_id'),

           ]);

           $success['seos']=New seoResource($seo);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','seo updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seo  $seo
     * @return \Illuminate\Http\Response
     */
    public function destroy($seo)
    {
        $seo =Seo::query()->find($seo);
        if (is_null($seo) || $seo->is_deleted==1){
            return $this->sendError("االكلمات المفتاحية غير موجودة","seo is't exists");
            }
           $seo->update(['is_deleted' => 1]);

           $success['seos']=New SeoResource($seo);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الكلمات المفتاحية بنجاح','seo deleted successfully');
    }
}
