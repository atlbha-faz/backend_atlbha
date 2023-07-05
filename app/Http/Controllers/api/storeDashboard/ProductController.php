<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\importsResource;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Importproduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

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

        $products = ProductResource::collection(Product::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());

        $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'purchasing_price', 'store_id']);
        $imports = importsResource::collection($import);

        $success['products'] = $products->merge($imports);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

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
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'selling_price' => ['required', 'numeric', 'gt:0'],
            'stock' => ['required', 'numeric', 'gt:0'],
            'cover' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'discount_price' => [ 'nullable','numeric'],
            'discount_percent' => [ 'nullable','numeric'],
            'SEOdescription' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => ['nullable','array'],
            'subcategory_id.*' => ['nullable', 'numeric',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->join('categories', 'id', 'parent_id');
                }),

            ],

            // 'store_id'=>'required|exists:stores,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $product = Product::create([
            'name' => $request->name,
            'for' => 'store',
            'description' => $request->description,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'cover' => $request->cover,
            'SEOdescription' => $request->SEOdescription,
            'discount_price' => $request->discount_price,
            'discount_percent' => $request->discount_percent,
            'subcategory_id' => implode(',', $request->subcategory_id),
            'category_id' => $request->category_id,
            'store_id' => auth()->user()->store_id,
        ]);

        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة منتج بنجاح', 'product Added successfully');
    }

    // public function deleteImport($product)
    // {
    //     $product =Importproduct::where('store_id',auth()->user()->store_id)->where('product_id',$product)->first();
    //     if (is_null($product)){
    //         return $this->sendError("المنتج غير موجود","product is't exists");
    //         }
    //        $product->delete();

    //        $success['status']= 200;
    //         return $this->sendResponse($success,'تم حذف المنتج بنجاح','product deleted successfully');
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $product = Product::query()->find($product);
        if (is_null($product) || $product->is_deleted == 1) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }

        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المنتج بنجاح', 'product showed successfully');
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

        $importproductt = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->where('importproducts.product_id', $id)
            ->first(['products.*', 'importproducts.price', 'importproducts.status']);

        if (!is_null($importproductt)) {
            $importproductt = $importproductt->makeHidden(['selling_price', 'purchasing_price', 'store_id']);
            $importproduct = Importproduct::where('product_id', $id)->first();

            $importproduct->update([
                'price' => $request->selling_price,
            ]);

            $success['products'] = new importsResource($importproductt);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم التعديل بنجاح', 'product updated successfully');
        } else {
            $product = Product::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
            if (is_null($product) || $product->is_deleted == 1) {
                return $this->sendError(" المنتج غير موجود", "product is't exists");
            }

            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|string|max:255',

                'description' => 'required|string',
                'selling_price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'numeric', 'gt:0'],
                'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'discount_price' => ['required', 'numeric'],
                'discount_percent' => ['required', 'numeric'],
                'SEOdescription' => 'nullable',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => ['nullable','array'],
                'subcategory_id.*' => ['nullable', 'numeric',
                    Rule::exists('categories', 'id')->where(function ($query) {
                        return $query->join('categories', 'id', 'parent_id');
                    }),

                ],

            ]);

            if ($validator->fails()) {
                # code...
                return $this->sendError(null, $validator->errors());
            }
            $product->update([
                'name' => $request->input('name'),
                'for' => 'store',
                'description' => $request->input('description'),
                'selling_price' => $request->input('selling_price'),
                'stock' => $request->input('stock'),
                'cover' => $request->cover,
                'SEOdescription' => $request->input('SEOdescription'),
                'discount_price' => $request->input('discount_price'),
                'discount_percent' => $request->input('discount_percent'),
                'category_id' => $request->input('category_id'),
                'subcategory_id' => implode(',', $request->subcategory_id),
                // 'store_id' => $request->input('store_id'),

            ]);

            $success['products'] = new ProductResource($product);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم التعديل بنجاح', 'product updated successfully');
        }
    }

    public function changeStatus($id)
    {
        $product = Product::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
        if (is_null($product) || $product->is_deleted == 1) {
            return $this->sendError("القسم غير موجودة", "product is't exists");
        }
        if ($product->status === 'active') {
            $product->update(['status' => 'not_active']);
        } else {
            $product->update(['status' => 'active']);
        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعدبل حالة القسم بنجاح', ' product status updared successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product = Product::where('id', $product)->where('store_id', auth()->user()->store_id)->first();
        if (is_null($product) || $product->is_deleted == 1) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }
        $product->update(['is_deleted' => 1]);

        $success['products'] = new ProductResource($product);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
    }

    public function deleteall(Request $request)
    {

        $importproducts = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->whereIn('importproducts.product_id', $request->id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['selling_price', 'purchasing_price', 'store_id']);
        if (count($importproducts) > 0) {
            foreach ($importproducts as $importproduct) {

                $product = Importproduct::where('store_id', auth()->user()->store_id)->where('product_id', $importproduct->id)->first();
                if (is_null($product)) {
                    return $this->sendError("المنتج غير موجود", "product is't exists");
                }
                $product->delete();

            }
        }

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                $product->update(['is_deleted' => 1]);
                $success['products'] = new ProductResource($product);

            }

        }

    }

    public function changeSatusall(Request $request)
    {
        $importproducts = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->whereIn('importproducts.product_id', $request->id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['selling_price', 'purchasing_price', 'store_id']);
        if (count($importproducts) > 0) {
            foreach ($importproducts as $importproduct) {

                $product = Importproduct::where('store_id', auth()->user()->store_id)->where('product_id', $importproduct->id)->first();
                if (is_null($product)) {
                    return $this->sendError("المنتج غير موجود", "product is't exists");
                }
                if ($product->status === 'active') {
                    $product->update(['status' => 'not_active']);
                } else {
                    $product->update(['status' => 'active']);
                }
                $success['products'] = new importsResource($importproduct);

            }

        }

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                if ($product->status === 'active') {
                    $product->update(['status' => 'not_active']);
                } else {
                    $product->update(['status' => 'active']);
                }
                $success['products'] = new ProductResource($product);

            }
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'product updated successfully');
        }
    }
    public function importProducts(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        try {

            Excel::import(new ProductsImport, $request->file);
            // Log::alert($row['cover']);
            // Log::info($row['cover']);

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إضافة المنتجات بنجاح', 'products Added successfully');
        } catch (ValidationException $e) {
            // Handle other import error
            // return "eroee";
            $failures = $e->failures();

            // Handle validation failures
            return $failures;
        }

    }
}
