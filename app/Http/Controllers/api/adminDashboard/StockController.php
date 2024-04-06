<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Imports\AdminProductImport;
use App\Models\Attribute;
use App\Models\Attribute_product;
use App\Models\Image;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Product;
use App\Models\Store;
use App\Models\Value;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

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

        $success['total_stock'] = Product::where('is_deleted', 0)->where('for', 'stock')->count();
        $success['finished_products'] = Product::where('is_deleted', 0)->where('for', 'stock')->where('stock', '0')->count();
        $success['finished_soon'] = Product::where('is_deleted', 0)->where('for', 'stock')->where('stock', '<', '20')->count();
        $date = Carbon::now()->subDays(7);
        $success['last_week_product_added'] = Product::where('is_deleted', 0)->where('for', 'stock')->where('created_at', '>=', $date)->count();
        $most_order = DB::table('importproducts')
            ->select('product_id', DB::raw('COUNT(*) as count'))
            ->groupBy('product_id')
            ->orderByDesc('count')->first();
        if ($most_order != null) {
            $success['most_order'] = Product::where('id', $most_order->product_id)->value('name');
        } else {
            $success['most_order'] = 0;
        }
        $success['products'] = ProductResource::collection(Product::with(['store', 'category' => function ($query) {
            $query->select('id', 'name', 'icon');
        }])->where('is_deleted', 0)->where('for', 'stock')->where('store_id', null)->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'purchasing_price', 'selling_price', 'stock', 'category_id', 'store_id', 'subcategory_id', 'created_at', 'description', 'short_description')->get());
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
            'name' => 'required|string|max:25',
            'description' => 'required|string',
            'purchasing_price' => ['required', 'numeric', 'gt:0'],
            'selling_price' => ['required', 'numeric', 'gte:' . (int) $request->purchasing_price],
            'stock' => ['required', 'numeric', 'gt:0'],

            'less_qty' => ['nullable', 'numeric', 'gt:0'],
            'images' => 'nullable|array',
            'images.*' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg', 'max:20000'],
            'cover' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],

            'SEOdescription' => 'nullable',
            'snappixel' => 'nullable|string',
            'tiktokpixel' => 'nullable|string',
            'twitterpixel' => 'nullable|string',
            'instapixel' => 'nullable|string',
            'short_description' => 'required|string|max:100',
            'robot_link' => 'nullable|string',
            'google_analytics' => 'nullable|url',
            'weight' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => ['nullable', 'array'],
            'subcategory_id.*' => ['nullable', 'numeric',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->join('categories', 'id', 'parent_id');
                }),
            ],
            'product_has_options' => 'nullable|in:0,1',
            'attribute' => 'array|required_if:product_has_options,1',
        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }

        $product = Product::create([
            'name' => $request->name,
            'for' => 'stock',
            // 'quantity' => $request->quantity,
            'less_qty' => $request->less_qty,
            'description' => $request->description,
            'purchasing_price' => $request->purchasing_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'cover' => $request->cover,
            'amount' => 1,
            'SEOdescription' => $request->SEOdescription != ""?json_encode(explode(',', $request->SEOdescription)):null,
            'snappixel' => $request->snappixel,
            'tiktokpixel' => $request->tiktokpixel,
            'twitterpixel' => $request->twitterpixel,
            'instapixel' => $request->instapixel,
            'short_description' => $request->short_description,
            'robot_link' => $request->robot_link,
            'google_analytics' => $request->google_analytics,
            'weight' => (!is_null($request->weight) ? $request->weight / 1000 : 0.5),
            'category_id' => $request->category_id,
            'subcategory_id' => $subcategory,
            'store_id' => null,
            'product_has_options' => $request->product_has_options,

        ]);
        $productid = $product->id;
        if ($request->hasFile("images")) {
            $files = $request->file("images");
            foreach ($files as $file) {
                $imageName = Str::random(10) . time() . '.' . $file->getClientOriginalExtension();
                $request['product_id'] = $productid;
                $request['image'] = $imageName;
                $filePath = 'images/product/' . $imageName;
                $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($file));
                Image::create($request->all());

            }
        }

        if ($request->has('attribute')) {
            if (!is_null($request->attribute)) {
                foreach ($request->attribute as $attribute) {

                    $option = new Attribute([
                        'name' => $attribute['title'],
                        'type' => $attribute['type'],
                    ]);
                    $option->save();

                    foreach ($attribute['value'] as $attributeValue) {
                        if (isset($attributeValue['image'])) {
                            $imageName = Str::random(10) . time() . '.' . $attributeValue['image']->getClientOriginalExtension();
                            $filePath = 'images/product/' . $imageName;
                            $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($attributeValue['image']));
                            if ($isFileUploaded) {
                                $attributeValue['image'] = asset('storage/images/product') . '/' . $imageName;
                            }

                        }
                        $value = new Value([
                            'attribute_id' => $option->id,
                            'value' => implode(',', $attributeValue),
                        ]);
                        $value->save();

                        $values[] = $value;
                        $valuesid[] = $value->id;
                    }

                    $attruibtevalues = Value::where('attribute_id', $option->id)->whereIn('id', $valuesid)->get();
                    $product->attributes()->attach($option->id, ['value' => json_encode($attruibtevalues)]);
                }
            }
        }
        if ($request->has('data')) {
            if (!is_null($request->data)) {

                foreach ($request->data as $data) {
                    $data['name'] = [
                        "ar" => implode(',', $data['name']),
                    ];

                    $option = new Option([
                        'price' => (isset($data['price']) && $data['price'] !== null) ? $data['price'] : null,
                        'discount_price' => (isset($data['discount_price']) && $data['discount_price'] !== null) ? $data['discount_price'] : null,
                        'quantity' => (isset($data['quantity']) && $data['quantity'] !== null) ? $data['quantity'] : null,
                        'name' => $data['name'],
                        'product_id' => $productid,
                        'default_option' => (isset($data['default_option']) && $data['default_option'] !== null) ? $data['default_option'] : 0,
                        'less_qty' => (isset($data['less_qty']) && $data['less_qty'] !== null) ? $data['less_qty'] : 0
                    ]);

                    $option->save();
                    $options[] = $option;
                }
            }
        }

        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة منتج بنجاح', 'product Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($product)
    {
        $product = Product::query()->where('for', 'stock')->find($product);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }

        $success['product'] = new productResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المنتج بنجاح', 'product showed successfully');

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
        $product = Product::query()->where('for', 'stock')->find($id);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:25',
            'description' => 'required|string',
            'less_qty' => ['nullable', 'numeric', 'gt:0'],
            'purchasing_price' => ['required', 'numeric', 'gt:0'],
            'selling_price' => ['required', 'numeric', 'gte:' . (int) $request->purchasing_price],
            'stock' => ['required', 'numeric', 'gt:0'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'images' => 'nullable|array',

            'SEOdescription' => 'nullable',
            'snappixel' => 'nullable|string',
            'tiktokpixel' => 'nullable|string',
            'twitterpixel' => 'nullable|string',
            'instapixel' => 'nullable|string',
            'short_description' => 'required|string|max:100',
            'robot_link' => 'nullable|string',
            'google_analytics' => 'nullable|url',
            'weight' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => ['nullable', 'array'],
            'subcategory_id.*' => ['nullable', 'numeric',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->join('categories', 'id', 'parent_id');
                }),
            ],
            'product_has_options' => 'nullable|in:0,1',
            'attribute' => 'array|required_if:product_has_options,1',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }

        $product->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'purchasing_price' => $request->input('purchasing_price'),
            'selling_price' => $request->input('selling_price'),
            'less_qty' => $request->input('less_qty'),
            'stock' => $request->input('stock'),
            'cover' => $request->cover,
            'amount' => 1,
            'SEOdescription' => $request->SEOdescription != ""?json_encode(explode(',', $request->SEOdescription)):null,
            'snappixel' => $request->snappixel,
            'tiktokpixel' => $request->tiktokpixel,
            'twitterpixel' => $request->twitterpixel,
            'instapixel' => $request->instapixel,
            'short_description' => $request->short_description,
            'robot_link' => $request->robot_link,
            'google_analytics' => $request->google_analytics,
            'weight' => (!is_null($request->weight) ? $request->weight / 1000 : 0.5),
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $subcategory,
            'product_has_options' => $request->product_has_options,

        ]);
        $productid = $product->id;

        if ($request->hasFile("images")) {
            $files = $request->images;

            $image_id = Image::where('product_id', $id)->pluck('id')->toArray();
            foreach ($image_id as $oid) {
                $image = Image::query()->find($oid);
                $image->update(['is_deleted' => $image->id]);

            }

            foreach ($files as $file) {
                if (is_uploaded_file($file)) {
                    $imageName = Str::random(10) . time() . '.' . $file->getClientOriginalExtension();
                    $request['product_id'] = $productid;
                    $request['image'] = $imageName;
                    $filePath = 'images/product/' . $imageName;
                    $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($file));
                    Image::create($request->all());
                } else {

                    $request['product_id'] = $productid;
                    $existingImagePath = $file;
                    $newImagePath = basename($file);
                    $request['image'] = $newImagePath;
                    Storage::copy($existingImagePath, $newImagePath);
                    Image::create($request->all());
                }

            }
        } else {

            $files = $request->images;
            $image_id = Image::where('product_id', $id)->pluck('id')->toArray();
            foreach ($image_id as $oid) {
                $image = Image::query()->find($oid);
                $image->update(['is_deleted' => $image->id]);
            }
            if ($files != null) {
                foreach ($files as $file) {
                    $imageName = time() . '_' . $file;
                    $request['product_id'] = $productid;
                    $existingImagePath = $file;
                    $newImagePath = basename($file);
                    $request['image'] = $newImagePath;
                    Storage::copy($existingImagePath, $newImagePath);
                    Image::create($request->all());

                }
            }
        }




        $preAttributes = Attribute_product::where('product_id', $productid)->get();
        if ($preAttributes != null) {
            foreach ($preAttributes as $preAttribute) {
                $preAttribute->delete();
            }
        }
        $preOptions = Option::where('product_id', $productid)->get();
        if ($preOptions != null) {
            foreach ($preOptions as $preOption) {
                $preOption->delete();
            }
        }
        if ($request->has('attribute')) {
            if (!is_null($request->attribute)) {
                foreach ($request->attribute as $attribute) {

                    $option = new Attribute([
                        'name' => $attribute['title'],
                        'type' => $attribute['type'],
                    ]);
                    $option->save();

                    foreach ($attribute['value'] as $attributeValue) {
                        if (isset($attributeValue['image'])) {
                            $imageName = Str::random(10) . time() . '.' . $attributeValue['image']->getClientOriginalExtension();
                            $filePath = 'images/product/' . $imageName;
                            $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($attributeValue['image']));
                            if ($isFileUploaded) {
                                $attributeValue['image'] = asset('storage/images/product') . '/' . $imageName;
                            }

                        }
                        $value = new Value([
                            'attribute_id' => $option->id,
                            'value' => implode(',', $attributeValue),
                        ]);
                        $value->save();

                        $values[] = $value;
                        $valuesid[] = $value->id;
                    }

                    $attruibtevalues = Value::where('attribute_id', $option->id)->whereIn('id', $valuesid)->get();
                    $product->attributes()->attach($option->id, ['value' => json_encode($attruibtevalues)]);
                }
            }
        }
        if ($request->has('data')) {
            if (!is_null($request->data)) {

                foreach ($request->data as $data) {
                    $data['name'] = [
                        "ar" => implode(',', $data['name']),
                    ];

                    $option = new Option([
                        'price' => (isset($data['price']) && $data['price'] !== null) ? $data['price'] : null,
                        'discount_price' => (isset($data['discount_price']) && $data['discount_price'] !== null) ? $data['discount_price'] : null,
                        'quantity' => (isset($data['quantity']) && $data['quantity'] !== null) ? $data['quantity'] : null,
                        'name' => $data['name'],
                        'product_id' => $productid,
                        'default_option' => (isset($data['default_option']) && $data['default_option'] !== null) ? $data['default_option'] : 0,
                        'less_qty' => (isset($data['less_qty']) && $data['less_qty'] !== null) ? $data['less_qty'] : 0
                    ]);

                    $option->save();
                    $options[] = $option;

                }
            }
        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deleteAll(Request $request)
    {

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('for', 'stock')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                $product->update(['is_deleted' => $product->id]);
                $success['products'] = new ProductResource($product);
                $preAttributes = Attribute_product::where('product_id', $product->id)->get();
                if ($preAttributes !== null) {
                    foreach ($preAttributes as $preAttribute) {
                        $preAttribute->delete();
                    }
                }
                $preOptions = Option::where('product_id', $product->id)->get();
                if ($preOptions !== null) {
                    foreach ($preOptions as $preOption) {
                        $preOption->delete();
                    }
                }

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

    public function addToStore($id)
    {
        $product = Product::query()->where('for', 'stock')->find($id);

        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجودة", "product is't exists");
        }

        if ($product->for === 'stock') {
            $product->update(['for' => 'etlobha']);
            //إستيراد الى متجر اطلبها
            $atlbha_id = Store::where('is_deleted', 0)->where('domain', 'atlbha')->pluck('id')->first();
            $importproduct = Importproduct::create([
                'product_id' => $product->id,
                'store_id' => $atlbha_id,
                'price' => $product->selling_price,
                'qty' => $product->stock,
            ]);
        }

        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم اضافة المنتج للسوق', 'product added to etlobha successfully');

    }

    public function importStockProducts(Request $request)
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

            Excel::import(new AdminProductImport, $request->file);


            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إضافة المنتجات بنجاح', 'products Added successfully');
        } catch (ValidationException $e) {
            //     // Handle other import error
            //     // return "eroee";
            $failures = $e->failures();

            //     // Handle validation failures
            return $failures;
        }

    }
    public function searchProductName(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $products = Product::where('is_deleted', 0)->where('store_id', null)->where('name', 'like', "%$query%")->orderBy('created_at', 'desc')
            ->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'purchasing_price', 'discount_price', 'stock', 'description', 'short_description')->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $products->total();
        $success['page_count'] = $products->lastPage();
        $success['current_page'] = $products->currentPage();
        $success['products'] = ProductResource::collection($products);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات  بنجاح', 'Product Information returned successfully');

    }

}
