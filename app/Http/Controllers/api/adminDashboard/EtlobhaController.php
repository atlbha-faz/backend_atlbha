<?php


namespace App\Http\Controllers\api\adminDashboard;
use Carbon\Carbon;
use App\Models\Image;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class EtlobhaController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
          $success['newProducts']=Product::where('is_deleted',0)->where('for','etlobha')->where('store_id',null)->where('created_at', '>=', Carbon::now()->subDay())->count();
          $success['not_active_products']=Product::where('is_deleted',0)->where('for','etlobha')->where('store_id',null)->where('status', 'not_active')->count();
          $success['about_to_finish_products']=Product::where('is_deleted',0)->where('for','etlobha')->where('store_id',null)->where('stock','<', '20')->count();
            $success['products']=ProductResource::collection(Product::where('is_deleted',0)->where('for','etlobha')->where('store_id',null)->get());
             $success['status']= 200;

              return $this->sendResponse($success,'تم ارجاع المنتجات بنجاح','products return successfully');

}



    public function store(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'sku'=>'required|string|unique:products',
            'description'=>'required|string',
            'purchasing_price'=>['required','numeric','gt:0'],
            'selling_price'=>['required','numeric','gt:0'],
            'stock'=>['required','numeric','gt:0'],
       
            'quantity'=>['required','numeric','gt:0'],
            'less_qty'=>['required','numeric','gt:0'],
            'images'=>'required|array',
            'images.*'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'cover'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'data'=>'required|array',
            'data.*.type'=>'required|in:brand,color,wight,size',
            'data.*.title'=>'required|string',
            'data.*.value'=>'required|array',
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
            return $this->sendError(null,$validator->errors());
        }
        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'for' => 'etlobha',
            'quantity' => $request->quantity,
            'less_qty' => $request->less_qty,
            'description' => $request->description,
            'purchasing_price' => $request->purchasing_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'cover' => $request->cover,
            'category_id' => $request->category_id,
            'subcategory_id' => implode(',', $request->subcategory_id),
            'store_id' => null,

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
            foreach($request->data as $data)
            {
                // dd($data['value']);
        //$request->input('name', []);
                $option= new Option([
                    'type' => $data['type'],
                    'title' => $data['title'],
                    'value' => implode(',',$data['value']),
                    'product_id' =>  $productid

                  ]);

                $option->save();
                $options[]=$option;
                }


         $success['products']=New ProductResource($product);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة منتج بنجاح','product Added successfully');
    }






    public function update(Request $request, $id)
    {
           $product =Product::query()->where('for','etlobha')->find($id);
           if (is_null($product) || $product->is_deleted==1){
           return $this->sendError(" المنتج غير موجود","product is't exists");
            }
           $input = $request->all();
           $validator =  Validator::make($input ,[
               'name'=>'required|string|max:255',
              'sku'=>'required|string|unique:products',
              'description'=>'required|string',
            'quantity'=>['required','numeric','gt:0'],
            'less_qty'=>['required','numeric','gt:0'],
              'purchasing_price'=>['required','numeric','gt:0'],
              'selling_price'=>['required','numeric','gt:0'],
              'stock'=>['required','numeric','gt:0'],
            'cover'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'images'=>'nullable|array',
            'images.*'=>['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
              'data'=>'required|array',
              'data.*.type'=>'required|in:brand,color,wight,size',
              'data.*.title'=>'required|string',
              'data.*.value'=>'required|array',
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
              'description' => $request->input('description'),
              'purchasing_price' => $request->input('purchasing_price'),
              'selling_price' => $request->input('selling_price'),
              'slug' =>$request->input('slug'),
              'quantity' =>$request->input('quantity'),
              'less_qty' =>$request->input('less_qty'),
              'stock' => $request->input('stock'),
              'cover' => $request->cover,
              'category_id' => $request->input('category_id'),
              'subcategory_id' =>  implode(',', $request->subcategory_id),

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


           public function specialStatus($id)
    {
        $product = Product::query()->where('for','etlobha')->find($id);
         if (is_null($product) || $product->is_deleted==1){
         return $this->sendError("المنتج غير موجود","product is't exists");
         }

       if($product->special === 'not_special'){
        $product->update(['special' => 'special']);
        }
        else{
        $product->update(['special' => 'not_special']);
        }
        $success['product']=New productResource($product);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة المنتج بنجاح','product updated successfully');

    }




    public function deleteall(Request $request)
    {

            $products =Product::whereIn('id',$request->id)->where('for','etlobha')->get();
            foreach($products as $product){
              if (is_null($product) || $product->is_deleted==1){
         return $this->sendError("المنتج غير موجود","product is't exists");}
              }
           foreach($products as $product)
           {
               $product->update(['is_deleted' => 1]);
            }
               $success['products']= ProductResource::collection($products);
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }

      public function changeStatusall(Request $request)
    {
        $products =Product::whereIn('id',$request->id)->where('for','etlobha')->get();
        foreach($products as $product){
          if (is_null($product) || $product->is_deleted==1){
         return $this->sendError("المنتج غير موجود","product is't exists");
          }}
        foreach($products as $product)
        {
        if($product->status === 'active'){
            $product->update(['status' => 'not_active']);
         }
         else{
        $product->update(['status' => 'active']);
    }
        }
        $success['products']= ProductResource::collection($products);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة القسم بنجاح',' product status updared successfully');

    }
}
