<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\importsResource;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Image;
use App\Models\Importproduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use VideoThumbnail;

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

        $products = ProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');}])
                ->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->where('for', 'store')->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'stock')->get()
        );

        $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
            ->select(['products.id', 'products.name', 'products.status', 'products.cover', 'products.special', 'products.store_id', 'products.created_at', 'products.category_id', 'products.subcategory_id', 'products.selling_price', 'products.stock',  'importproducts.qty','importproducts.price', 'importproducts.status'])->get()->makeHidden(['products.*status', 'selling_price', 'store_id']);
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
            'for' => 'store',
            'description' => 'required|string',
            'selling_price' => ['required', 'numeric', 'gt:0'],
            'stock' => ['required', 'numeric', 'gt:0'],
            'cover' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'discount_price' => ['nullable', 'numeric'],
            'images' => 'nullable|array',
            'images.*' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg', 'max:2048'],
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

                // if (strpos($mimeType, 'video/') === 0) {
                //     $thumbnail = VideoThumbnail::createThumbnail(
                //         storage_path('app/public/images/product/'.$imageName),

                //         storage_path('app/public/images/product/thumb'),
                //         'thumbnail.jpg',
                //         2,
                //         1920,
                //         1080
                //     );
                //     // dd($filePath);

                //     dd($thumbnail);
                // }

            }
        }
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

        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }

        $newproduct = Importproduct::where('store_id', auth()->user()->store_id)->where('product_id', $product->id)->first();
        if ($newproduct) {
            $newimportproduct = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)->where('importproducts.product_id', $product->id)
                ->first(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
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
                'name' => 'required|string|max:255',

                'description' => 'required|string',
                'selling_price' => ['required', 'numeric', 'gt:0'],
                'stock' => ['required', 'numeric', 'gt:0'],
                'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
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
            $success['products'] = new ProductResource($product);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم التعديل بنجاح', 'product updated successfully');
        }
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
                $success['products'] = new ProductResource($product);

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
        $product = Product::query()->where('store_id', auth()->user()->store_id)->find($id);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }

        if ($product->special === 'not_special') {
            $product->update(['special' => 'special']);
        } else {
            $product->update(['special' => 'not_special']);
        }
        $success['product'] = new productResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'product updated successfully');

    }

    // public function duplicateProduct($productId)
    // {
    //     // Find the existing product by ID
    //     $existingProduct = Product::where('store_id', auth()->user()->store_id)->find($productId);

    //     if (!$existingProduct || $existingProduct->is_deleted != 0) {
    //         // Handle case where the product does not exist
    //         // or display an error message to the user
    //         return $this->sendError("المنتج غير موجود", "product is't exists");

    //     }

    //     // Create a new instance of the Product model
    //     $newProduct = new Product();

    //     // Assign the attributes of the existing product to the new product
    //     $newProduct->name = $existingProduct->name;
    //     $newProduct->selling_price = $existingProduct->selling_price;
    //     $newProduct->discount_price = $existingProduct->discount_price;
    //     $newProduct->category_id = $existingProduct->category_id;
    //     $newProduct->subcategory_id = $existingProduct->subcategory_id;
    //     $newProduct->SEOdescription = $existingProduct->SEOdescription;
    //     $newProduct->for = $existingProduct->for;
    //     $newProduct->store_id = $existingProduct->store_id;

    //     $newProduct->stock = $existingProduct->stock;
    //     // Assign other attributes as needed

    //     // Save the new product to the database
    //     $newProduct->save();

    //     // Duplicate the image file
    //     $existingImagePath = $existingProduct->cover;

    //     if ($existingImagePath) {
    //         $newImagePath =  basename($existingImagePath);
    //         Storage::copy($existingImagePath, $newImagePath);

    //         // Associate the new image path with the new product
    //         $newProduct->cover = $newImagePath;
    //         $newProduct->save();
    //     }
    //     $success['products'] = new ProductResource($newProduct);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم  تكرار المنتج بنجاح', 'product updated successfully');

    //     // Redirect or return a response as desired
    // }
}
