<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Importproduct;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ImportproductResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ImportproductController extends BaseController
{
   public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function etlobhaShow()
    {
            $success['count_products']=(Importproduct::where('store_id', auth()->user()->store_id)->count());
            $success['products']=ProductResource::collection(Product::where('is_deleted',0)->where('for','etlobha')->where('store_id',null)->get());
             $success['status']= 200;

              return $this->sendResponse($success,'تم ارجاع المنتجات بنجاح','products return successfully');

}
  public function store(Request $request){

     $input = $request->all();
        $validator =  Validator::make($input ,[
            'price'=>['required','numeric','gt:0'],
            'product_id'=>'required'
        ]);
         if ($validator->fails())
          {
            return $this->sendError(null,$validator->errors());
        }
         $importproduct = Importproduct::create([
            'product_id' => $request->product_id,
            'store_id'=> auth()->user()->store_id,
            'price' => $request->price
         ]);
        $success['importproducts']=New ImportproductResource($importproduct);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة الاستيراد بنجاح','importproduct Added successfully');
    }




}
