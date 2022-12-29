<?php


namespace App\Http\Controllers\api\adminDashboard;
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
        {
            $success['products']=ProductResource::collection(Product::where('is_deleted',0)->where('for','etlobha')->where('store_id',null)->get());
             $success['status']= 200;

              return $this->sendResponse($success,'تم ارجاع المنتجات بنجاح','products return successfully');
    }
}



    public function store(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'sku'=>'required|string',
            'description'=>'required|string',
            'purchasing_price'=>['required','numeric','gt:0'],
            'selling_price'=>['required','numeric','gt:0'],
            'stock'=>['required','numeric','gt:0'],
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
        //$request->input('name', []);
                $option= new Option([
                    'type' => $data['type'],
                    'title' => $data['title'],
                    'value' => $data['value'],
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

            //   $image_id = Image::where('product_id', $id)->pluck('id')->toArray();
            //   foreach ($image_id as $oid) {
            //     if (!(in_array($oid, array_column($files, 'id')))) {
            //       $image = Image::query()->find($oid);
            //       $image->update(['is_deleted' => 1]);
            //     }
            //   }


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


          public function changeSpecial($id)
          {
              $product = Product::query()->find($id);
              if (is_null($product) || $product->is_deleted==1  || $product->for=='store'){
               return $this->sendError("المنتج غير موجودة","product is't exists");
               }
              if($product->special === 'yes'){
                  $product->update(['special' => 'no']);
           }
          else{
              $product->update(['special' => 'yes']);
          }
              $success['products']=New ProductResource($product);
              $success['status']= 200;
               return $this->sendResponse($success,'تم تعدبل  بنجاح','product special updared successfully');

          }




    public function destroy(array $id)
    {

            $products =Product::whereIn('id',$id)->get();
           foreach($products as $product)
           {
               $product->update(['is_deleted' => 1]);
            }
               $success['products']= ProductResource::collection($products);
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    }
}