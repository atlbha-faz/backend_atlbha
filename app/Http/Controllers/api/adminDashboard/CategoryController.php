<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;
class CategoryController extends BaseController
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
        $success['categories']=CategoryResource::collection(Category::where('is_deleted',0)->where('parent_id',null)->where('for','etlobha')->where('store_id',null)->get());
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


            $input = $request->all();
            $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255',
                'icon'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
                'data.*.name'=>'nullable|string|max:255',
                'data.*.id' => 'nullable|numeric',
            ]);
            if ($validator->fails())
            {
                return $this->sendError(null,$validator->errors());
            }

            $cat=Category::orderBy('id', 'desc')->first();
          if(is_null($cat)){
          $number = 0001;
          }
          else{

          $number=$cat->number;
          $number= ((int) $number) +1;
          }

            $category =Category::create([
                'name' => $request->name,
                'number'=> str_pad($number, 4, '0', STR_PAD_LEFT),
                'icon' => $request->icon,
                'parent_id'=>null,
                'for'=>'etlobha',
                'store_id'=> null,
              ]);


  if($request->data){
    foreach($request->data as $data)
    {

            $cat=Category::orderBy('id', 'desc')->first();
          $number=$cat->number;
          $number= ((int) $number) +1;

        $subcategory= new Category([
                'number'=> str_pad($number, 4, '0', STR_PAD_LEFT),
            'name' => $data['name'],
            'parent_id' => $category->id,
            'for'=> 'etlobha',
            'store_id'=> null,
              ]);

        $subcategory->save();

    }
}

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
        if ( is_null($category) || $category->is_deleted==1 || $category->for=='store'){
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
        if (is_null($category) || $category->is_deleted==1 || $category->for=='store'){
         return $this->sendError("القسم غير موجودة","category is't exists");
         }
         if($category->parent_id == null)
         {
         $categories = Category::where('parent_id', $category->id)->get();

        foreach ($categories as $subcategory) {

        if($subcategory->status === 'active'){
            $subcategory->update(['status' => 'not_active']);
     }
    else{
        $subcategory->update(['status' => 'active']);
        }
        }
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
    public function update(Request $request,  $category)
    {
        $category = Category::where('id', $category)->where('store_id', null)->first();
        if (is_null($category) ||  $category->is_deleted==1 || $category->for=='store'){
            return $this->sendError("التصنيف غير موجودة"," Category is't exists");
       }
       $category_id=$category->id;
        // if($request->parent_id == null){

            $input = $request->all();
           $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
             'icon'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'data.*.name'=>'nullable|string|max:255',
            'data.*.id' => 'nullable|numeric',
           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }




           $category->update([
               'name' => $request->input('name'),
                'icon' =>$request->icon,
           ]);

       $subcategory = Category::where('parent_id', $category_id);


       // dd($request->$data['id']);
        $subcategories_id = Category::where('parent_id', $category_id)->pluck('id')->toArray();
        foreach ($subcategories_id as $oid) {
        if (!(in_array($oid, array_column($request->data, 'id')))) {
            $subcategory = Category::query()->find($oid);
            $subcategory->update(['is_deleted' => 1]);
        }
        }
if($request->data){
     foreach ($request->data as $data) {
         $sub_cat = Category::find($data['id']);

           if(!is_null($sub_cat)){
             $number = $sub_cat->number;
         }else{
             $cat=Category::orderBy('id', 'desc')->first();
          $number=$cat->number;
          $number= ((int) $number) +1;
             $number = str_pad($number, 4, '0', STR_PAD_LEFT);
         }
      $subcategories[] = Category::updateOrCreate([
         'id'=>$data['id'],
      ], [
        'name' => $data['name'],
         'number'=> $number,
       'parent_id' => $category_id,
        'for'=>'etlobha',
        'is_deleted' => 0

      ]);
    }
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
        $category =Category::query()->where('for','etlobha')->where('store_id', null)->find($category);
        if (is_null($category) || $category->is_deleted==1){
            return $this->sendError("القسم غير موجودة","category is't exists");
            }
            if($category->parent_id == null){
            $categories = Category::where('parent_id', $category->id)->get();

           foreach ($categories as $subcategory) {

            $subcategory->update(['is_deleted' => 1]);
                 }
                }
           $category->update(['is_deleted' => 1]);

           $success['categories']=New CategoryResource($category);
           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف القسم بنجاح','category deleted successfully');
    }

     public function deleteall(Request $request)
    {

            $categorys =Category::whereIn('id',$request->id)->where('for','etlobha')->where('store_id', null)->where('is_deleted',0)->get();
            if(count($categorys)>0){
           foreach($categorys as $category)
           {


             $category->update(['is_deleted' => 1]);
            $success['categorys']=New CategoryResource($category);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف التصنيف بنجاح','category deleted successfully');
             }
             else{
                $success['status']= 200;
            return $this->sendResponse($success,'المدخلات غير موجودة','id does not exit');
            }
             }
       public function changeSatusall(Request $request)
            {

                    $categorys =Category::whereIn('id',$request->id)->where('for','etlobha')->where('is_deleted',0)->get();
                    if(count($categorys)>0){
                foreach($categorys as $category)
                {

                    if($category->status === 'active'){
                $category->update(['status' => 'not_active']);
                }
                else{
                $category->update(['status' => 'active']);
                }
                $success['categorys']= New CategoryResource($category);

                    }
                    $success['status']= 200;

                return $this->sendResponse($success,'تم تعديل حالة التصنيف بنجاح','category updated successfully');
           }
           else{
            $success['status']= 200;
        return $this->sendResponse($success,'المدخلات غير موجودة','id does not exit');
        }
        }
}
