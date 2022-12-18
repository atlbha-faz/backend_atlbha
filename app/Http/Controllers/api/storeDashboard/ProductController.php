<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
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
       {
       $success['products']=ProductResource::collection(Product::where('is_deleted',0)->where('store_id',auth()->user()->store_id)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المنتجات بنجاح','products return successfully');
    }
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
            'sku'=>'required|string',
            'for'=>'required|in:store,etlobha',
            'description'=>'required|string',
            'purchasing_price'=>['required','numeric','gt:0'],
            'selling_price'=>['required','numeric','gt:0'],
            'quantity'=>['required','numeric','gt:0'],
            'less_qty'=>['required','numeric','gt:0'],
            'stock'=>['required','numeric','gt:0'],
            'tags'=>'required',
            'cover'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'discount_price'=>['required','numeric'],
            'discount_percent'=>['required','numeric'],
            'category_id'=>'required|exists:categories,id',
                 'subcategory_id'=>['required','array'],
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
            'for' => $request->for,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'purchasing_price' => $request->purchasing_price,
            'selling_price' => $request->selling_price,
            'less_qty' => $request->less_qty,
            'stock' => $request->stock,
            'cover' => $request->cover,
            'tags' => implode(',', $request->tags),
           'discount_price'=>$request->discount_price,
            'discount_percent'=>$request->discount_percent,
            'subcategory_id' => implode(',', $request->subcategory_id),
            'category_id' => $request->category_id,
            'store_id'=> auth()->user()->store_id,
          ]);
 $productid =$product->id;
              if($request->hasFile("images")){
                $files=$request->file("images");
                foreach($files as $file){
                    $imageName=time().'_'.$file->getClientOriginalName();
                    $request['product_id']= $productid ;
                    $request['image']=$imageName;
                    // $file->move(\public_path("/images"),$imageName);
                     $file->store('images/product', 'public');
                    Image::create($request->all());

                }
            }


         $success['products']=New ProductResource($product);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة منتج بنجاح','product Added successfully');
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
         $product =Product::query()->find($id);
         if (is_null($product) || $product->is_deleted==1){
         return $this->sendError(" المنتج غير موجود","product is't exists");
          }
         $input = $request->all();
         $validator =  Validator::make($input ,[
             'name'=>'required|string|max:255',
            'sku'=>'required|string',
             'for'=>'required|in:store,etlobha',
            'description'=>'required|string',
            'purchasing_price'=>['required','numeric','gt:0'],
            'selling_price'=>['required','numeric','gt:0'],
            'quantity'=>['required','numeric','gt:0'],
            'less_qty'=>['required','numeric','gt:0'],
            'stock'=>['required','numeric','gt:0'],
            'tags'=>'required',
            'cover'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'discount_price'=>['required','numeric'],
            'discount_percent'=>['required','numeric'],
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
            'for' => $request->input('for'),
            'description' => $request->input('description'),
            'purchasing_price' => $request->input('purchasing_price'),
            'quantity' => $request->input('quantity'),
            'less_qty' => $request->input('less_qty'),
            'stock' => $request->input('stock'),
            'tags' =>implode(',',$request->input('tags')),
            'cover' => $request->input('cover'),
            'discount_price'=>$request->input('discount_price'),
            'discount_percent'=>$request->input('discount_percent'),
            'category_id' => $request->input('category_id'),
             'subcategory_id' => $request->input('subcategory_id'),
            // 'store_id' => $request->input('store_id'),


         ]);
         if($request->hasFile("images")){
            $files=$request->file("images");
            foreach($files as $file){
                $imageName=time().'_'.$file->getClientOriginalName();
                $request["product_id"]=$id;
                $request["image"]=$imageName;
                $file->store('images/product', 'public');
                Image::create($request->all());

            }
        }

            $success['products']=New ProductResource($product);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','product updated successfully');
        }


     public function changeStatus($id)
    {
        $product = Product::query()->find($id);
        if (is_null($product) || $product->is_deleted==1){
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
        $product =Product::query()->find($product);
        if (is_null($product) || $product->is_deleted==1){
            return $this->sendError("المنتج غير موجود","product is't exists");
            }
           $product->update(['is_deleted' => 1]);

           $success['products']=New ProductResource($product);
           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }
}
