<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ImportproductResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Importproduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImportproductController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function etlobhaShow()
    {
        $success['count_products'] = (Importproduct::where('store_id', auth()->user()->store_id)->count());
        $success['categories'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('for', 'store')->where('parent_id', null)->where('store_id', null)->get());
        $imports=Importproduct::where('store_id', auth()->user()->store_id)->get()->pluck('product_id')->toArray();
        $success['products'] = ProductResource::collection(Product::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->whereNotIn('id',$imports)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }
    public function store(Request $request)
    {
        $importedproduct = Importproduct::where('product_id', $request->product_id)->first();
        if ($importedproduct) {
            return $this->sendError(" تم استيراده مسبقا ", "imported");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'price' => ['required', 'numeric', 'gt:0'],
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $importproduct = Importproduct::create([
            'product_id' => $request->product_id,
            'store_id' => auth()->user()->store_id,
            'price' => $request->price,
        ]);
        $success['importproducts'] = new ImportproductResource($importproduct);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الاستيراد بنجاح', 'importproduct Added successfully');
    }

    public function show($product)
    {
        $product = Product::query()->where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->find($product);
        if (is_null($product) || $product->is_deleted == 1) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المنتج بنجاح', 'product showed successfully');
    }

    public function updateimportproduct(Request $request, $id)
    {
        $importproduct = Importproduct::where('product_id', $id)->first();
        if ($importproduct != null) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'price' => ['required', 'numeric', 'gt:0'],

            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }

            $importproduct->update([
                'price' => $request->price,
            ]);
            $success['importproducts'] = new ImportproductResource($importproduct);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل الاستيراد بنجاح', 'importproduct updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'الاستيراد غير صحيح', 'id does not exit');
        }
    }

}
