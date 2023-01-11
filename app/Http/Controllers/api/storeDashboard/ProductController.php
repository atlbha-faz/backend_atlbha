<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Importproduct;
use Illuminate\Validation\Rule;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ProductController extends BaseController
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


        //  dd($id);
       $success['products']=ProductResource::collection(Product::where('is_deleted',0)
        ->where(function($query){
        $import=Importproduct::where('store_id',auth()->user()->store_id)->pluck('product_id')->toArray();
        $query->where('store_id',auth()->user()->store_id)
        ->OrWhere('store_id',null)->whereIn('id', $import);
        })->get());

    //   $success['importproducts']=ProductResource::collection(Product::where('is_deleted',0)->whereIn('product_id',$id->product_id)->get());


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
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'sku'=>'required|string|unique:products',
            'description'=>'required|string',
            'selling_price'=>['required','numeric','gt:0'],
            'stock'=>['required','numeric','gt:0'],
            'cover'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'discount_price'=>['required','numeric'],
            'discount_percent'=>['required','numeric'],
            'category_id'=>'required|exists:categories,id',
            'subcategory_id'=>['array'],
            'subcategory_id.*'=>['required','numeric',
            Rule::exists('categories', 'id')->where(function ($query) {
            return $query->join('categories', 'id', 'parent_id');
        }),


            ]

            // 'store_id'=>'required|exists:stores,id',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'for' => 'store',
            'description' => $request->description,


            'selling_price' => $request->selling_price,

            'stock' => $request->stock,
            'cover' => $request->cover,

           'discount_price'=>$request->discount_price,
            'discount_percent'=>$request->discount_percent,
            'subcategory_id' => implode(',', $request->subcategory_id),
            'category_id' => $request->category_id,
            'store_id'=> auth()->user()->store_id,
          ]);



         $success['products']=New ProductResource($product);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة منتج بنجاح','product Added successfully');
    }

    public function deleteImport($product)
    {
        $product =Importproduct::where('store_id',auth()->user()->store_id)->where('product_id',$product)->first();
        if (is_null($product)){
            return $this->sendError("المنتج غير موجود","product is't exists");
            }
           $product->delete();

           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $product= Product::query()->find($product);
        if (is_null($product) || $product->is_deleted==1){
               return $this->sendError("المنتج غير موجود","product is't exists");
               }
              $success['products']=New ProductResource($product);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض المنتج بنجاح','product showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
  {
         $product =Product::where('id',$id)->where('store_id',auth()->user()->store_id)->get();
         if (is_null($product) || $product->is_deleted==1 ){
         return $this->sendError(" المنتج غير موجود","product is't exists");
          }
         $input = $request->all();
         $validator =  Validator::make($input ,[
             'name'=>'required|string|max:255',
            'sku'=>'required|string|unique:products',
            'description'=>'required|string',
            'selling_price'=>['required','numeric','gt:0'],
            'stock'=>['required','numeric','gt:0'],
            'cover'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'discount_price'=>['required','numeric'],
            'discount_percent'=>['required','numeric'],
            'category_id'=>'required|exists:categories,id',
            'subcategory_id'=>['array'],
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
            'for' => 'store',
            'description' => $request->input('description'),
            'selling_price' => $request->input('selling_price'),
            'stock' => $request->input('stock'),
            'cover' => $request->input('cover'),
            'discount_price'=>$request->input('discount_price'),
            'discount_percent'=>$request->input('discount_percent'),
            'category_id' => $request->input('category_id'),
             'subcategory_id' => $request->input('subcategory_id'),
            // 'store_id' => $request->input('store_id'),


         ]);


            $success['products']=New ProductResource($product);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','product updated successfully');
        }


     public function changeStatus($id)
    {
        $product = Product::where('id',$id)->where('store_id',auth()->user()->store_id)->get();
        if (is_null($product) || $product->is_deleted==1 ){
         return $this->sendError("القسم غير موجودة","product is't exists");
         }
        if($product->status === 'active'){
            $product->update(['status' => 'not_active']);
     }
    else{
        $product->update(['status' => 'active']);
    }
        $success['products']=New ProductResource($product);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة القسم بنجاح',' product status updared successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product =Product::where('id',$product)->where('store_id',auth()->user()->store_id)->get();
        if (is_null($product) || $product->is_deleted==1){
            return $this->sendError("المنتج غير موجود","product is't exists");
            }
           $product->update(['is_deleted' => 1]);

           $success['products']=New ProductResource($product);
           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }

   

      public function deleteall(Request $request)
    {

            $products =Product::whereIn('id',$request->id)->where('store_id',auth()->user()->store_id)->get();
           foreach($products as $product)
           {
             if (is_null($product) || $product->is_deleted==1 ){
                    return $this->sendError("المنتج غير موجودة","product is't exists");
             }
             $product->update(['is_deleted' => 1]);
            $success['products']=New ProductResource($product);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }

       public function changeSatusall(Request $request)
            {

                    $products =Product::whereIn('id',$request->id)->where('store_id',auth()->user()->store_id)->get();
                foreach($products as $product)
                {
                    if (is_null($product) || $product->is_deleted==1){
                        return $this->sendError("  المنتج غير موجودة","product is't exists");
              }
                    if($product->status === 'active'){
                $product->update(['status' => 'not_active']);
                }
                else{
                $product->update(['status' => 'active']);
                }
                $success['products']= New ProductResource($product);

                    }
                    $success['status']= 200;

                return $this->sendResponse($success,'تم تعديل حالة المنتج بنجاح','product updated successfully');
           }
}
