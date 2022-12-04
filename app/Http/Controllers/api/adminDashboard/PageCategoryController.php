<?php

namespace App\Http\Controllers\api\adminDashboard;

use Illuminate\Http\Request;
use App\Models\Page_category;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Page_categoryResource;
use App\Http\Controllers\api\BaseController as BaseController;

class PageCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['PageCategories']=Page_categoryResource::collection(Page_category::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع تصنيفات الصفحة بنجاح','PageCategories return successfully');
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
            'name'=>'required|string|max:255'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $page_category = Page_category::create([
            'name' => $request->name,
          ]);


         $success['PageCategories']=New Page_categoryResource($page_category);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة تصنيف الصفحة بنجاح','page_category Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\page_category  $page_category
     * @return \Illuminate\Http\Response
     */
    public function show($page_category)
    {
        $page_category= Page_category::query()->find($page_category);
        if ($page_category->is_deleted==1){
               return $this->sendError("التصنيف غير موجودة","Page_category is't exists");
               }
              $success['page_categories']=New Page_categoryResource($page_category);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض التصنيف بنجاح','Page_category showed successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\page_category  $page_category
     * @return \Illuminate\Http\Response
     */
    public function edit(page_category $page_category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\page_category  $page_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $page_category)
    {
        $page_category =Page_category::query()->find($page_category);
        if ($page_category->is_deleted==1){
         return $this->sendError("التصنيف غير موجودة","page_category is't exists");
          }
         $input = $request->all();
         $validator =  Validator::make($input ,[
          'name'=>'required|string|max:255',
         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         }
         $page_category->update([
            'name' => $request->input('name')

         ]);

            $success['page_categorys']=New Page_categoryResource($page_category);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','page_category updated successfully');
        }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\page_category  $page_category
     * @return \Illuminate\Http\Response
     */
    public function destroy($page_category)
    {
        $page_category =Page_category::query()->find($page_category);
        if ($page_category->is_deleted==1){
            return $this->sendError("التصنيف غير موجودة","page_category is't exists");
            }
           $page_category->update(['is_deleted' => 1]);

           $success['page_categories']=New Page_categoryResource($page_category);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف التصنيف بنجاح','page_category deleted successfully');
    }

     public function changeStatus($id)
    {
        $page_category = Page_category::query()->find($id);
         if ($page_category->is_deleted==1){
         return $this->sendError("التصنيف غير موجودة","page_category is't exists");
         }

        if($page_category->status === 'active'){
        $page_category->update(['status' => 'not_active']);
        }
        else{
        $page_category->update(['status' => 'active']);
        }
        $success['page_categories']=New Page_categoryResource($page_category);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة التصنيف بنجاح','page_category updated successfully');


    }
}