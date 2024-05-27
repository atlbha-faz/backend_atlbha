<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\importsResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImportproductController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function etlobhaShow(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $success['count_products'] = (Importproduct::where('store_id', auth()->user()->store_id)->count());
        if (!$request->has('show_categories') && $request->input('show_categories') !== false) {
            $success['categories'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('parent_id', null)->where('store_id', null)->get());
        }
        $products = Product::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->whereNot('stock', 0)->orderByDesc('created_at')->select('id', 'name', 'cover', 'selling_price', 'purchasing_price', 'stock', 'less_qty', 'created_at', 'category_id', 'subcategory_id');
        if ($request->has('category_id')) {
            $products = $products->where('category_id', $request->category_id);
        }
        if ($request->has('subcategory_id')) {
            $terms = $request->subcategory_id;
            $products = $products->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('subcategory_id', 'like', "%$term%");
                };
            });
        }
        $products = $products->paginate($count);
        $products = ProductResource::collection($products);
        $success['page_count'] = $products->lastPage();
        $success['total_result'] = $products->total();
        $success['current_page'] = $products->currentPage();
        $success['products'] = $products;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }

    
    public function show($product)
    {
        $product = Product::query()->where('is_deleted', 0)->where('store_id', null)->find($product);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المنتج بنجاح', 'product showed successfully');
    }

    public function updateImportProduct(Request $request, $id)
    {
        $importproduct = Importproduct::where('product_id', $id)->where('store_id', auth()->user()->store_id)->first();
        $purchasing_price = Product::where('id', $id)->value('purchasing_price');

        if ($importproduct != null) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'price' => ['required', 'numeric',
                    'gte:' . $purchasing_price],
                'discount_price_import' => ['nullable', 'numeric'],
            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }

            $importproduct->update([
                'price' => $request->price,
                'discount_price_import' => $request->discount_price,

            ]);
            if ($request->has('data') && !is_null($request->data)) {
                foreach ($request->data as $data) {

                    $option = Option::where('id', $data['option_id'])->first();
                    $option->update([
                        'price' => $data['price'],
                        'discount_price' => $data['discount_price'],
                        'default_option' => (isset($data['default_option']) && $data['default_option'] !== null) ? $data['default_option'] : 0,
                    ]);

                }
            }
            $importproduct = Importproduct::with('product')->where('store_id', auth()->user()->store_id)->where('product_id', $id)->first();

            $success['importproducts'] = new importsResource($importproduct);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل الاستيراد بنجاح', 'importproduct updated successfully');
        } else {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }
    }

}
