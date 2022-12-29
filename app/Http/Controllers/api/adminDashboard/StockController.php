<?php

namespace App\Http\Controllers\api\adminDashboard;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\api\BaseController as BaseController;

class StockController extends BaseController
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

         $success['total_stock']=Product::where('is_deleted',0)->where('for','etlobha')->count();
         $success['finished_products']=Product::where('is_deleted',0)->where('for','etlobha')->where('stock','0')->count();
         $success['finished_soon']=Product::where('is_deleted',0)->where('for','etlobha')->where('stock', '<','20')->count();
         $date = Carbon::now()->subDays(7);
         $success['last_week_product_added']=Product::where('is_deleted',0)->where('for','etlobha')->where('created_at', '>=', $date)->count();
         $success['most_order']=0;
          $success['products']=ProductResource::collection(Product::where('is_deleted',0)->where('for','etlobha')->where('store_id',null)->get());
             $success['status']= 200;

              return $this->sendResponse($success,'تم ارجاع المنتجات بنجاح','products return successfully');

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
        {
           $product =Product::query()->find($id);
           if (is_null($product) || $product->is_deleted==1  || $product->for=='store'){
           return $this->sendError(" المنتج غير موجود","product is't exists");
            }
           $input = $request->all();
           $validator =  Validator::make($input ,[
               'name'=>'required|string|max:255',
              'sku'=>'required|string',
              'description'=>'required|string',
              'purchasing_price'=>['required','numeric','gt:0'],
              'selling_price'=>['required','numeric','gt:0'],
              'stock'=>['required','numeric','gt:0'],
              'cover'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
              'data' => 'required|array',
              'category_id'=>'required|exists:categories,id',
              'subcategory_id'=>['required','array'],
              'subcategory_id.*'=>['required','numeric',
              Rule::exists('categories', 'id')->where(function ($query) {
              return $query->join('categories', 'id', 'parent_id');
          }),
              ]
           ]);

           if ($validator->fails())
           {
              # code...
              return $this->sendError(null,$validator->errors());
           }
           $product->update([
              'name' => $request->input('name'),
              'sku' => $request->input('sku'),
              'description' => $request->input('description'),
              'purchasing_price' => $request->input('purchasing_price'),
              'selling_price' => $request->input('selling_price'),
              'slug' =>$request->input('slug'),
              'stock' => $request->input('stock'),
              'cover' => $request->input('cover'),
              'category_id' => $request->input('category_id'),
              'subcategory_id' => $request->input('subcategory_id'),

           ]);
           if($request->hasFile("images")){
              $files=$request->file("images");

              $image_id = Image::where('product_id', $id)->pluck('id')->toArray();
              foreach ($image_id as $oid) {
                  $image = Image::query()->find($oid);
                  $image->update(['is_deleted' => 1]);

              }


              foreach($files as $file){
                  $imageName=time().'_'.$file->getClientOriginalName();
                  $request["product_id"]=$id;
                  $request["image"]=$imageName;
                  $file->store('images/product', 'public');
                  Image::create($request->all());

              }
          }

          $option = Option::where('product_id', $id);


          // dd($request->$data['id']);
          $options_id = Option::where('product_id', $id)->pluck('id')->toArray();
          foreach ($options_id as $oid) {
            if (!(in_array($oid, array_column($request->data, 'id')))) {
              $option = Option::query()->find($oid);
              $option->update(['is_deleted' => 1]);
            }
          }

          foreach ($request->data as $data) {
            $options[] = Option::updateOrCreate([
              'id' => $data['id'],
              'product_id' => $id,
              'is_deleted' => 0,
            ], [
              'type' => $data['type'],
              'title' => $data['title'],
              'value' => $data['value'],
              'product_id' => $id
            ]);
          }


              $success['products']=New ProductResource($product);
              $success['status']= 200;

              return $this->sendResponse($success,'تم التعديل بنجاح','product updated successfully');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deleteall(Request $request)
    {


            $products =Product::whereIn('id',$request->id)->get();
           foreach($products as $product)
           {
             if (is_null($product) || $product->is_deleted==1 || $product->for=="store"){
                   return $this->sendError("المنتج غير موجودة"," product is't exists");
             }
             $product->update(['is_deleted' => 1]);
        $success['products']= New ProductResource($product);

            }
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }



}
