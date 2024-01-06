<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Attribute_product;
use App\Models\Image;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Order;
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

class EtlobhaController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $success['newProducts'] = Product::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->where('created_at', '>=', Carbon::now()->subDay())->count();
        $more_sales = $products = DB::table('order_items')->join('products', 'order_items.product_id', '=', 'products.id')->where('products.store_id', null)->where('products.for', 'etlobha')->where('products.is_deleted', 0)
            ->select('products.id', DB::raw('sum(order_items.total_price) as sales'), DB::raw('sum(order_items.quantity) as count'))
            ->groupBy('order_items.product_id')->orderBy('count', 'desc')->first();
        if ($more_sales != null) {
            $success['more_sales'] = Product::where('id', $more_sales->id)->value('name');
        } else {
            $success['more_sales'] = 0;
        }
        $success['not_active_products'] = Product::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->where('status', 'not_active')->count();
        $success['about_to_finish_products'] = Product::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->where('stock', '<', '20')->count();
        $success['products'] = ProductResource::collection(Product::with(['store', 'category' => function ($query) {
            $query->select('id', 'name');
        }])->where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'purchasing_price', 'selling_price', 'stock', 'category_id', 'store_id', 'subcategory_id', 'created_at', 'admin_special')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }

    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:25',
            'description' => 'required|string|max:100',
            'purchasing_price' => ['required', 'numeric', 'gt:0'],
            'selling_price' => ['required', 'numeric', 'gte:' . (int) $request->purchasing_price],
            'stock' => ['required', 'numeric', 'gt:0'],
            // 'amount' => ['required', 'numeric'],
            // 'quantity' => ['required_if:amount,0', 'numeric', 'gt:0'],
            'less_qty' => ['nullable', 'numeric', 'gt:0'],
            'images' => 'nullable|array',
            // 'data' => 'nullable|array',
            // 'data.*.type' => 'required|in:brand,color,wight,size',
            // 'data.*.title' => 'required|string',
            // 'data.*.value' => 'required|array',
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
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        if (($request->hasFile("cover"))) {
            $validator = Validator::make($input, [
                'cover' => 'required |image| mimes:jpeg,png,jpg,gif,svg| max:2048',
                // 'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],

            ]);
            $cover = $request->cover;

        } else {
            $validator = Validator::make($input, [
                'cover' => 'required |string| max:2048',
                // 'images.*' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }

            $existingImagePath = $request->cover;
            $newImagePath = basename($request->cover);
            Storage::copy($existingImagePath, $newImagePath);
            $cover = $newImagePath;
        }

        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }

        $product = Product::create([
            'name' => $request->name,
            'for' => 'etlobha',
            // 'quantity' => $request->quantity,
            'less_qty' => $request->less_qty,
            'description' => $request->description,
            'purchasing_price' => $request->purchasing_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'cover' => $cover,
            'amount' => 1,
            'SEOdescription' => $request->SEOdescription,
            'snappixel' => $request->snappixel,
            'tiktokpixel' => $request->tiktokpixel,
            'twitterpixel' => $request->twitterpixel,
            'instapixel' => $request->instapixel,
            'short_description' => $request->short_description,
            'robot_link' => $request->robot_link,
            'weight' => (!is_null($request->weight) ? $request->weight / 1000 : 0.5),
            'google_analytics' => $request->google_analytics,
            'category_id' => $request->category_id,
            'subcategory_id' => $subcategory,
            'store_id' => null,
            'amount' => $request->amount,

        ]);
        $productid = $product->id;
        //إستيراد الى متجر اطلبها
        $atlbha_id = Store::where('is_deleted', 0)->where('domain', 'atlbha')->pluck('id')->first();
        $importproduct = Importproduct::create([
            'product_id' => $product->id,
            'store_id' => $atlbha_id,
            'price' => $product->selling_price,
            'qty' => $product->stock,
        ]);
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
        } else {
            if ($request->has('images')) {
                $files = $request->images;
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

        // if (!is_null($request->data)) {
        //     foreach ($request->data as $data) {
        //         // dd($data['value']);
        //         //$request->input('name', []);
        //         $option = new Option([
        //             'type' => $data['type'],
        //             'title' => $data['title'],
        //             'value' => implode(',', $data['value']),
        //             'product_id' => $productid,

        //         ]);

        //         $option->save();
        //         $options[] = $option;
        //     }
        // }
        if ($request->has('attribute')) {
            if (!is_null($request->attribute)) {
                foreach ($request->attribute as $attribute) {

                    $option = new Attribute([
                        'name' => $attribute['title'],
                        'type' => $attribute['type'],
                    ]);
                    $option->save();

                    foreach ($attribute['value'] as $attributeValue) {

                        $value = new Value([
                            'attribute_id' => $option->id,
                            'value' => $attributeValue,
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
                        'price' => $data['price'],
                        'quantity' => $data['quantity'],
                        'name' => $data['name'],
                        'product_id' => $productid,

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

    public function update(Request $request, $id)
    {
        $product = Product::query()->where('for', 'etlobha')->find($id);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:25',
            'description' => 'required|string|max:100',
            // 'amount' => ['required', 'numeric'],

            // 'quantity' => ['required_if:amount,0', 'numeric', 'gt:0'],
            'less_qty' => ['required', 'numeric', 'gt:0'],
            'purchasing_price' => ['required', 'numeric', 'gt:0'],
            'selling_price' => ['required', 'numeric', 'gte:' . (int) $request->purchasing_price],
            'stock' => ['required', 'numeric', 'gt:0'],

            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'images' => 'nullable|array',
            // 'images.*' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg', 'max:20000'],
            // 'data' => 'nullable|array',
            // 'data.*.type' => 'required|in:brand,color,wight,size',
            // 'data.*.title' => 'required|string',
            // 'data.*.value' => 'required|array',
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
            // 'quantity' => $request->input('quantity'),
            'less_qty' => $request->input('less_qty'),
            'stock' => $request->input('stock'),
            'cover' => $request->cover,
            'amount' => 1,
            'SEOdescription' => $request->input('SEOdescription'),
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
            'amount' => $request->amount,

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
                // $imageName = Str::random(10) . time() . '.' . $file->getClientOriginalExtension();
                // $request['product_id'] = $productid;
                // $request['image'] = $imageName;
                // $filePath = 'images/product/' . $imageName;
                // $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($file));
                // Image::create($request->all());
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

        // $option = Option::where('product_id', $id);

        // if (!is_null($request->data)) {
        //     $options_id = Option::where('product_id', $id)->pluck('id')->toArray();
        //     foreach ($options_id as $oid) {
        //         if (!(in_array($oid, array_column($request->data, 'id')))) {
        //             $option = Option::query()->find($oid);
        //             $option->update(['is_deleted' => 1]);
        //         }
        //     }

        //     foreach ($request->data as $data) {
        //         if (!isset($data['id'])) {
        //             $data['id'] = null;
        //         }

        //         $options[] = Option::updateOrCreate([
        //             'id' => $data['id'],
        //             'product_id' => $id,
        //             'is_deleted' => 0,
        //         ], [
        //             'type' => $data['type'],
        //             'title' => $data['title'],
        //             'value' => $data['value'],
        //             'product_id' => $id,
        //         ]);
        //     }
        // }
        else {
            if ($request->has('images')) {
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
        }
        $preAttributes = Attribute_product::where('product_id', $productid)->get();
        if($preAttributes != null)
        {
        foreach ($preAttributes as $preAttribute) {
            $preAttribute->delete();
        }
    }
        $preOptions = Option::where('product_id', $productid)->get();
          if($preOptions != null){
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

                    $value = new Value([
                        'attribute_id' => $option->id,
                        'value' => $attributeValue,
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
                    'price' => $data['price'],
                    'quantity' => $data['quantity'],
                    'name' => $data['name'],
                    'product_id' => $productid,

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

    public function show($product)
    {
        $product = Product::query()->where('for', 'etlobha')->find($product);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }

        $success['product'] = new productResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المنتج بنجاح', 'product showed successfully');

    }

    public function specialStatus($id)
    {
        $product = Product::query()->where('for', 'etlobha')->find($id);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }

        if ($product->admin_special === 'not_special') {
            $product->update(['admin_special' => 'special']);
        } else {
            $product->update(['admin_special' => 'not_special']);
        }
        $success['product'] = new productResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'product updated successfully');

    }

    public function deleteall(Request $request)
    {

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('for', 'etlobha')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                $imports = Importproduct::where('product_id', $product->id)->get();
                if (count($imports) > 0) {
                    foreach ($imports as $import) {

                        $import->delete();
                    }
                }

                $product->update(['is_deleted' => $product->id]);
            }
            $success['products'] = ProductResource::collection($products);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

    public function changeStatusall(Request $request)
    {
        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('for', 'etlobha')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                if ($product->status === 'active') {
                    $product->update(['status' => 'not_active']);
                } else {
                    $product->update(['status' => 'active']);
                }
            }
            $success['products'] = ProductResource::collection($products);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم تعدبل حالة المنتج بنجاح', ' product status updared successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }

    }
    public function statistics($product)
    {

        $product = Product::where('id', $product)->where('is_deleted', 0)->where('for', 'etlobha')->first();
        if ($product != null) {
            $success['product'] = new productResource($product);
            $success['import_count'] = $product->importproduct->count();
            $success['number_of_sold_product'] = Order::whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id)->where('order_status', 'completed');
            })->count();
            $success['total'] = $success['number_of_sold_product'] * $product->selling_price;
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم العرض بنجاح', ' product show successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير موجودة', 'id does not exit');
        }
    }
}
