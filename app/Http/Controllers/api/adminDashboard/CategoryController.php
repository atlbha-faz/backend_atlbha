<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;
class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['categories']=CategoryResource::collection(Category::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع التصنيفات بنجاح','categories return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->parent_id == null)
        {

            $input = $request->all();
            $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255',
                'icon'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
                'for'=>'required',
            ]);
            if ($validator->fails())
            {
                return $this->sendError(null,$validator->errors());
            }

            $cat=Category::orderBy('id', 'desc')->first();
          if(is_null($cat)){
          $number = 0001;
          }else{

          $number=$cat->number;
          $number= ((int) $number) +1;
          }

            $category =Category::create([
                'name' => $request->name,
                'number'=> str_pad($number, 4, '0', STR_PAD_LEFT),
                'icon' => $request->icon,
                'parent_id'=>$request->parent_id,
                'for'=>$request->for,
                'store_id'=> $request->store_id,
              ]);

        }
        else{


            $input = $request->all();
            $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255',
                'for'=>'required|in:store,etlobha',
                'parent_id'=>'required'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(null,$validator->errors());
            }

            $cat=Category::orderBy('id', 'desc')->first();
        if(is_null($cat)){
          $number = 0001;
        }else{

          $number=$cat->number;
          $number= ((int) $number) +1;
        }


            $category =Category::create([
                'name' => $request->name,
                'number'=> str_pad($number, 4, '0', STR_PAD_LEFT),
                'parent_id'=>$request->parent_id,
                'for'=>$request->for,
                'store_id'=>$request->store_id,
              ]);


    }
    // dd($category );
    $success['categories']=New CategoryResource($category);
    $success['status']= 200;

     return $this->sendResponse($success,'تم إضافة التصنيف بنجاح','Category Added successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        $category= Category::query()->find($category);
        if ( is_null($category) || $category->is_deleted==1){
               return $this->sendError("القسم غير موجودة","Category is't exists");
               }
              $success['categories']=New CategoryResource($category);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض القسم بنجاح','Category showed successfully');
    }

     public function children($parnet)
    {
        $category= Category::where('parent_id',$parnet)->where('is_deleted',0)->get();

              $success['categories']=New CategoryResource($category);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الاقسام الفرعية بنجاح','sub_Category showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    public function changeStatus($id)
    {
        $category = Category::query()->find($id);
        if (is_null($category) || $category->is_deleted==1){
         return $this->sendError("القسم غير موجودة","category is't exists");
         }
        if($category->status === 'active'){
            $category->update(['status' => 'not_active']);
     }
    else{
        $category->update(['status' => 'active']);
    }
        $success['categories']=New CategoryResource($category);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة القسم بنجاح',' category status updared successfully');

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if (is_null($category) ||  $category->is_deleted==1){
            return $this->sendError("التصنيف غير موجودة"," Category is't exists");
       }
        if($request->parent_id == null){

            $input = $request->all();
           $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'icon'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'for'=>'required',

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $category->update([
               'name' => $request->input('name'),
                'icon' =>$request->input('icon'),
                'for' =>$request->input('for'),
                'store_id' =>$request->input('store_id')
           ]);
           }
           else{
            $input = $request->all();
            $validator =  Validator::make($input ,[
             'name'=>'required|string|max:255',
             'for'=>'required',
             'parent_id'=>'required'

            ]);
            if ($validator->fails())
            {
                # code...
                return $this->sendError(null,$validator->errors());
            }
            $category->update([
                'name' => $request->input('name'),
                'icon' =>$request->input('icon'),
                'parent_id' =>$request->input('parent_id'),
                'for' =>$request->input('for'),
                'store_id' =>$request->input('store_id')
            ]);
           }

           $success['categories']=New CategoryResource($category);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($category)
    {
        $category =Category::query()->find($category);
        if (is_null($category) || $category->is_deleted==1){
            return $this->sendError("القسم غير موجودة","category is't exists");
            }
           $category->update(['is_deleted' => 1]);

           $success['activities']=New CategoryResource($category);
           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف القسم بنجاح','category deleted successfully');
    }
}
