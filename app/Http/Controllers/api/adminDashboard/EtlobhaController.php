<?php

namespace App\Http\Controllers\api\adminDashboard;

use Carbon\Carbon;
use App\Models\Image;
use App\Models\Order;
use App\Models\Store;
use App\Models\Value;
use App\Models\Option;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Importproduct;
use Illuminate\Validation\Rule;
use App\Models\Attribute_product;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\EtlobhaStoreRequest;
use App\Http\Requests\EtlobhaUpdateRequest;
use App\Http\Controllers\api\BaseController as BaseController;

class EtlobhaController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
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
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data=Product::with(['store', 'category' => function ($query) {
            $query->select('id', 'name');
        }])->where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->orderByDesc('created_at')->select(['id', 'name', 'status', 'cover', 'special', 'purchasing_price', 'selling_price', 'stock', 'category_id', 'store_id', 'subcategory_id', 'created_at', 'admin_special']);
        if ($request->has('category_id')) {
            $data = $data->where('category_id', $request->category_id);
        }
        if ($request->has('subcategory_id')) {
            $terms = $request->subcategory_id;
            $data = $data->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('subcategory_id', 'like', "%$term%");
                };
            });
        }

        $data= $data->paginate($count);
        $success['products'] = ProductResource::collection($data);
        $success['status'] = 200;
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();
        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }

    public function store(EtlobhaStoreRequest $request)
    {
        $input = $request->all();
        if (($request->hasFile("cover"))) {
            $validator = Validator::make($input, [
                'cover' => 'image| mimes:jpeg,png,jpg,gif,svg| max:1048',
            ]);
            $cover = $request->cover;

        } else {
            $validator = Validator::make($input, [
                'cover' => 'string| max:1048',

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
            'product_has_options' => $request->product_has_options,

        ]);
        $productid = $product->id;

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
        $options = array();
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
                        'less_qty' => (isset($data['less_qty']) && $data['less_qty'] !== null) ? $data['less_qty'] : 0,
                    ]);

                    $option->save();
                    $options[] = $option;
                }
            }
        }
        //إستيراد الى متجر اطلبها
        $atlbha_id = Store::where('is_deleted', 0)->where('domain', 'atlbha-store.com')->pluck('id')->first();
        $importproduct = Importproduct::create([
            'product_id' => $product->id,
            'store_id' => $atlbha_id,
            'price' => $product->selling_price,
            'qty'=>$product->stock

        ]);
        $options = Option::where('is_deleted', 0)->where('product_id', $product->id)->where('importproduct_id',null)->get();
        foreach ($options as $option) {
            $newOption = $option->replicate();
            $newOption->product_id = null;
            $newOption->original_id = $option->id;
            $newOption->importproduct_id = $importproduct->id;
            $newOption->price = $option->price;
            $newOption->save();
        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة منتج بنجاح', 'product Added successfully');

    }

    public function update(EtlobhaUpdateRequest $request, $id)
    {
        $input = $request->all();
        $product = Product::query()->where('for', 'etlobha')->find($id);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
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
            'SEOdescription' => $request->SEOdescription,
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
            // 'amount' => $request->amount,
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
                        'less_qty' => (isset($data['less_qty']) && $data['less_qty'] !== null) ? $data['less_qty'] : 0,

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

    public function deleteAll(Request $request)
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
            $success['products'] = ProductResource::collection($products);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

    public function changeStatusAll(Request $request)
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
    public function import(Request $request){
        $imports=Product::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->get();
        foreach ($imports as $import){
            $imported=Importproduct::where('product_id', $import->id)->first();
            if ($imported == null){
                $atlbha_id = Store::where('is_deleted', 0)->where('domain','atlbha-store.com')->pluck('id')->first();
                $importproduct = Importproduct::create([
                    'product_id' => $import->id,
                    'store_id' => $atlbha_id,
                    'price' => $import->selling_price,
                    'qty'=>$import->stock
        
                ]);
            }
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم العرض بنجاح', ' product show successfully');

    }
}
