<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

use function PHPSTORM_META\map;

class PageController extends BaseController
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
        $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',auth()->user()->store_id)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع  الصفحة بنجاح','Pages return successfully');
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
            'title'=>'required|string|max:255',
            'page_content'=>'required',
            'seo_title'=>'required',
            'seo_link'=>'required',
            'seo_desc'=>'required',
            'tags'=>'required',
            // 'store_id'=>'required|exists:stores,id',
            // 'user_id'=>'required|exists:users,id',

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $page = Page::create([
            'title' => $request->title,
            'page_content' => $request->page_content,
            'seo_title' => $request->seo_title,
            'seo_link' => $request->seo_link,
            'seo_desc' => $request->seo_desc,
            'tags' => implode(',', $request->tags),
           'store_id'=> auth()->user()->store_id,
            'user_id'=>auth()->user()->id,
            'name'=> $request->name,
          ]);
           //$request->input('name', []);
          $page->page_categories()->attach(explode(',', $request->name));

         $success['Pages']=New PageResource($page);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة تصنيف الصفحة بنجاح','page_category Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show($page)
    {
        $page= Page::query()->find($page);
        if (is_null($page) || $page->is_deleted==1){
               return $this->sendError("الصفحة غير موجودة","Page is't exists");
               }
              $success['pages']=New PageResource($page);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الصفحة بنجاح','Page showed successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        if ($page->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة","Page is't exists");
       }

            $input = $request->all();
           $validator =  Validator::make($input ,[
            'title'=>'required|string|max:255',
            'page_content'=>'required',
            'seo_title'=>'required',
            'seo_link'=>'required',
            'seo_desc'=>'required',
            'tags'=>'required',
            // 'store_id'=>'required|exists:stores,id',
            // 'usre_id'=>'required|exists:users,id',
           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $page->update([
               'title' => $request->input('title'),
               'page_content' => $request->input('page_content'),
               'seo_title' => $request->input('seo_title'),
               'seo_link' => $request->input('seo_link'),
               'seo_desc' => $request->input('seo_desc'),
            //    'store_id' => $request->input('store_id'),
            //    'user_id' => $request->input('user_id'),
               'tags' => implode(',',$request->input('tags')),
               'name'=> $request->input('name'),
           ]);
           //$request->input('name', []);
           if($request->name!=null){
           $page->page_categories()->sync(explode(',', $request->name));
           }
           $success['pages']=New PageResource($page);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','page updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy($page)
    {
        $page =Page::query()->find($page);
        if (is_null($page) || $page->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة","page is't exists");
            }
           $page->update(['is_deleted' => 1]);

           $success['pages']=New PageResource($page);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الصفحة بنجاح','page deleted successfully');
    }

      public function changeStatus($id)
    {
        $page = Page::query()->find($id);
         if (is_null($page) || $page->is_deleted==1){
         return $this->sendError("  الصفحة غير موجودة","page is't exists");
         }

        if($page->status === 'active'){
        $page->update(['status' => 'not_active']);
        }
        else{
        $page->update(['status' => 'active']);
        }
        $success['pages']=New PageResource($page);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة الصفحة بنجاح','page updated successfully');


    }

}
