<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\importsResource;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Attribute;
use App\Models\Attribute_product;
use App\Models\Image;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Product;
use App\Models\Value;
use Google\Service\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $products = ProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');
        }])
                ->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('for', 'store')->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'purchasing_price', 'discount_price', 'stock', 'description', 'is_import', 'original_id', 'short_description')->paginate($count)
        );

        // $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
        //     ->select(['products.id', 'products.name', 'products.status', 'products.cover', 'importproducts.special', 'products.store_id', 'products.created_at', 'products.category_id', 'products.subcategory_id', 'products.selling_price', 'products.stock', 'importproducts.qty', 'importproducts.price', 'importproducts.status', 'products.description', 'products.short_description'])->get()->makeHidden(['products.*status', 'selling_price', 'store_id']);
        // $imports = importsResource::collection($import);

        $collection = $products;
        $success['page_count'] = $products->lastPage();
        $success['current_page'] = $products->currentPage();
        $success['products'] = $collection;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }
    public function products(Request $request)
    {if ($request->has('page')) {
        $products = ProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');
        }])
                ->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('for', 'store')->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'purchasing_price', 'discount_price', 'stock', 'description', 'short_description')->paginate(8)
        );
        $success['page_count'] = $products->lastPage();

    } else {
        $products = ProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');
        }])
                ->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('for', 'store')->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'purchasing_price', 'discount_price', 'stock', 'description', 'short_description')->get()
        );

    }
        $collection = $products;

        $success['products'] = $collection;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');}
    public function importedProducts()
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
            ->select(['products.id', 'products.name', 'products.status', 'products.cover', 'products.special', 'products.store_id', 'products.created_at', 'products.category_id', 'products.subcategory_id', 'products.selling_price', 'products.stock', 'importproducts.qty', 'importproducts.price', 'importproducts.status', 'products.description', 'products.short_description'])->paginate($count)->makeHidden(['products.*status', 'selling_price', 'store_id']);
        $forpage = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)

            ->select(['products.id', 'products.name', 'products.status', 'products.cover', 'products.special', 'products.store_id', 'products.created_at', 'products.category_id', 'products.subcategory_id', 'products.selling_price', 'products.stock', 'importproducts.qty', 'importproducts.price', 'importproducts.status', 'products.description', 'products.short_description'])->paginate($count);

        $imports = importsResource::collection($import);
        $success['page_count'] = $forpage->lastPage();

        $success['products'] = $imports;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }

    // for mobile application

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
            'for' => 'store',
            'description' => 'required|string',
            'selling_price' => ['required', 'numeric', 'gt:0'],
            'stock' => ['required', 'numeric', 'gt:0'],
            'cover' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
            'discount_price' => ['nullable', 'numeric'],
            'images' => 'nullable|array',
            'images.*' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg', 'max:1048'],
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
            'amount' => 'nullable|in:0,1',
            'product_has_options' => 'nullable|in:0,1',
            'attribute' => 'array|required_if:product_has_options,1',
            'data.*.price' => 'numeric|required_if:amount,1',
            'data.*.quantity' => 'numeric|required_if:amount,1',

            // 'store_id'=>'required|exists:stores,id',
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
            'for' => "store",
            'description' => $request->description,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'cover' => $request->cover,
            'SEOdescription' => $request->SEOdescription,
            'discount_price' => $request->discount_price,
            'snappixel' => $request->snappixel,
            'tiktokpixel' => $request->tiktokpixel,
            'twitterpixel' => $request->twitterpixel,
            'instapixel' => $request->instapixel,
            'short_description' => $request->short_description,
            'robot_link' => $request->robot_link,
            'google_analytics' => $request->google_analytics,
            'weight' => (!is_null($request->weight) ? $request->weight / 1000 : 0.5),
            'subcategory_id' => $subcategory,
            'category_id' => $request->category_id,
            'store_id' => auth()->user()->store_id,
            'amount' => $request->amount,
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
                $mimeType = $file->getClientMimeType();

            }
        }
        if ($request->has('attribute') && !is_null($request->attribute)) {
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

        if ($request->has('data') && !is_null($request->data)) {
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

                ]);

                $option->save();
                $options[] = $option;

            }

        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة منتج بنجاح', 'product Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

        $product = Product::query()->find($product);

        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }

        $newproduct = Importproduct::where('store_id', auth()->user()->store_id)->where('product_id', $product->id)->first();
        if ($newproduct) {
            $newimportproduct = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->where('importproducts.product_id', $product->id)
                ->first(['products.*', 'importproducts.qty', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
            $success['product'] = new importsResource($newimportproduct);

        } else {
            $success['product'] = new ProductResource($product);

        }

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
            $importproductt = $importproductt->makeHidden(['selling_price', 'store_id']);
            $importproduct = Importproduct::where('product_id', $id)->first();

            $importproduct->update([
                'price' => $request->selling_price,
            ]);
            $newimportproduct = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->where('importproducts.product_id', $id)
                ->first(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);

            $success['products'] = new importsResource($newimportproduct);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم التعديل بنجاح', 'product updated successfully');
        } else {
            $product = Product::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
            if (is_null($product) || $product->is_deleted != 0) {
                return $this->sendError(" المنتج غير موجود", "product is't exists");
            }

            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|string|max:25',
                'description' => 'required|string',
                'selling_price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'numeric', 'gt:0'],
                'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
                'discount_price' => ['nullable', 'numeric'],
                'images' => 'nullable|array',
                //'images.*' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg', 'max:20000'],
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
                'amount' => 'nullable|in:0,1',
                'data.*.price' => 'numeric|required_if:amount,1',
                'data.*.quantity' => 'numeric|required_if:amount,1',
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
                'selling_price' => $request->input('selling_price'),
                'stock' => $request->input('stock'),
                'cover' => $request->cover,
                'SEOdescription' => $request->input('SEOdescription'),
                'discount_price' => $request->input('discount_price'),
                'snappixel' => $request->snappixel,
                'tiktokpixel' => $request->tiktokpixel,
                'twitterpixel' => $request->twitterpixel,
                'instapixel' => $request->instapixel,
                'weight' => (!is_null($request->weight) ? $request->weight / 1000 : 0.5),
                'short_description' => $request->short_description,
                'robot_link' => $request->robot_link,
                'google_analytics' => $request->google_analytics,
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $subcategory,
                'amount' => $request->amount,
                'product_has_options' => $request->product_has_options,
                // 'store_id' => $request->input('store_id'),

            ]);
            $productid = $product->id;
            if ($request->hasFile("images")) {
                $files = $request->file("images");
                $image_id = Image::where('product_id', $id)->pluck('id')->toArray();
                foreach ($image_id as $oid) {
                    $image = Image::query()->find($oid);
                    $image->update(['is_deleted' => $image->id]);

                }

                if ($request->hasFile("images")) {
                    $files = $request->images;

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
            foreach ($preAttributes as $preAttribute) {
                $preAttribute->delete();
            }

            $preOptions = Option::where('product_id', $productid)->get();
            foreach ($preOptions as $preOption) {
                $preOption->delete();
            }
            if ($request->has('attribute') && !is_null($request->attribute)) {
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

        if ($request->has('data') && !is_null($request->data)) {

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
                ]);

                $option->save();
                $options[] = $option;

            }

        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'product updated successfully');
    }

    public function updateCategory(Request $request)
    {
        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();

        if (count($products) < 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");

        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'subcategory_id' => ['nullable', 'array'],
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
        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }

        foreach ($products as $product) {

            $product->update([
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $subcategory,

            ]);

        }
        // $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'product updated successfully');

    }
    public function changeStatus($id)
    {
        $product = Product::where('id', $id)->where('store_id', auth()->user()->store_id)->first();
        if (is_null($product) || $product->is_deleted != 0) {
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
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }
        $product->update(['is_deleted' => $product->id]);

        $success['products'] = new ProductResource($product);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
    }

    public function deleteall(Request $request)
    {

        $importproducts = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->whereIn('importproducts.product_id', $request->id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['selling_price', 'store_id']);

        if (count($importproducts) > 0) {
            foreach ($importproducts as $importproduct) {

                $product = Importproduct::where('store_id', auth()->user()->store_id)->where('product_id', $importproduct->id)->first();
                if (is_null($product)) {
                    return $this->sendError("المنتج غير موجود", "product is't exists");
                }
                $mainProduct = Product::where('id', $importproduct->id)->where('is_deleted', 0)->first();
                $comments = $mainProduct->comment->where('store_id', auth()->user()->store_id);
                if ($comments != null) {
                    foreach ($comments as $comment) {
                        $comment->update(['is_deleted' => $comment->id]);
                    }
                }
                $product->delete();
            }
        }

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();

        if (count($products) > 0) {
            foreach ($products as $product) {

                $product->update(['is_deleted' => $product->id]);
                $comments = $product->comment;
                if ($comments != null) {
                    foreach ($comments as $comment) {
                        $comment->update(['is_deleted' => $comment->id]);
                    }
                }
                // $success['products'] = new ProductResource($product);

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

        }
        if (count($products) < 1 && count($importproducts) < 1) {
            return $this->sendError("المنتج غير موجود", "product is't exists");

        }
        $productss = ProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');
        }])
                ->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('for', 'store')->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'purchasing_price', 'discount_price', 'stock', 'description', 'short_description')->get()
        );

        $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
            ->select(['products.id', 'products.name', 'products.status', 'products.cover', 'products.special', 'products.store_id', 'products.created_at', 'products.category_id', 'products.subcategory_id', 'products.selling_price', 'products.stock', 'importproducts.qty', 'importproducts.price', 'importproducts.status', 'products.description', 'products.short_description'])->get()->makeHidden(['products.*status', 'selling_price', 'store_id']);
        $imports = importsResource::collection($import);

        $success['products'] = $productss->merge($imports);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');

    }
    public function deleteItems(Request $request)
    {

        $importproducts = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['selling_price', 'store_id']);
        if (count($importproducts) > 0) {
            foreach ($importproducts as $importproduct) {

                $product = Importproduct::where('store_id', auth()->user()->store_id)->where('product_id', $importproduct->id)->first();
                if (is_null($product)) {
                    return $this->sendError("المنتج غير موجود", "product is't exists");
                }
                $mainProduct = Product::where('id', $importproduct->id)->where('is_deleted', 0)->first();
                $comments = $mainProduct->comment->where('store_id', auth()->user()->store_id);
                if ($comments != null) {
                    foreach ($comments as $comment) {
                        $comment->update(['is_deleted' => $comment->id]);
                    }
                }
                $product->delete();
            }
        }

        $products = Product::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                $product->update(['is_deleted' => $product->id]);
                $comments = $product->comment;
                if ($comments != null) {
                    foreach ($comments as $comment) {
                        $comment->update(['is_deleted' => $comment->id]);
                    }
                }
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

        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');

    }

    public function changeSatusall(Request $request)
    {
        $importproducts = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->whereIn('importproducts.product_id', $request->id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['selling_price', 'store_id']);
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

        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'product updated successfully');
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
    public function specialStatus($id)
    {
        $product = Product::query()->where('is_deleted', 0)->find($id);

        if (is_null($product)) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }
        if ($product->store_id == auth()->user()->store_id) {
            if ($product->special === 'not_special') {
                $product->update(['special' => 'special']);
            } else {
                $product->update(['special' => 'not_special']);
            }
        } else {
            $importproduct = Importproduct::where('product_id', $id)->where('store_id', auth()->user()->store_id)->first();
            if ($importproduct->special === 'not_special') {
                $importproduct->update(['special' => 'special']);
            } else {
                $importproduct->update(['special' => 'not_special']);
            }

        }

        $success['product'] = new productResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'product updated successfully');

    }
    public function searchProductName(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('name', 'like', "%$query%")->orderBy('created_at', 'desc')
            ->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'purchasing_price', 'discount_price', 'stock', 'description', 'is_import', 'original_id', 'short_description')->paginate(10);

        $success['query'] = $query;
        $success['total_result'] = $products->total();
        $success['page_count'] = $products->lastPage();
        $success['current_page'] = $products->currentPage();
        $success['products'] = ProductResource::collection($products);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات  بنجاح', 'Product Information returned successfully');

    }
    public function searchImportProductName(Request $request)
    {
        $query = $request->input('query');

        $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->where('products.name', 'like', "%$query%")
            ->select(['products.id', 'products.name', 'products.status', 'products.cover', 'products.special', 'products.store_id', 'products.created_at', 'products.category_id', 'products.subcategory_id', 'products.selling_price', 'products.stock', 'importproducts.qty', 'importproducts.price', 'importproducts.status', 'products.description', 'products.short_description'])->paginate(10)->makeHidden(['products.*status', 'selling_price', 'store_id']);
        $import_page = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->where('products.name', 'like', "%$query%")
            ->select(['products.id', 'products.name', 'products.status', 'products.cover', 'products.special', 'products.store_id', 'products.created_at', 'products.category_id', 'products.subcategory_id', 'products.selling_price', 'products.stock', 'importproducts.qty', 'importproducts.price', 'importproducts.status', 'products.description', 'products.short_description'])->paginate(10);

        $success['query'] = $query;
        $success['total_result'] = $import_page->total();
        $success['page_count'] = $import_page->lastPage();
        $success['current_page'] = $import_page->currentPage();
        $success['products'] = importsResource::collection($import);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات  بنجاح', 'Product Information returned successfully');

    }

}
