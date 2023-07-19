<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Imports\AdminProductImport;
use App\Models\Image;
use App\Models\Option;
use App\Models\Product;
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
        $success['products'] = ProductResource::collection(Product::where('is_deleted', 0)->where('for', 'stock')->where('store_id', null)->get());
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
            'purchasing_price' => ['required', 'numeric', 'gt:0'],
            'selling_price' => ['required', 'numeric', 'gte:purchasing_price'],
            'stock' => ['required', 'numeric', 'gt:0'],
            // 'amount' => ['required', 'numeric'],
            'quantity' => ['required_if:amount,0', 'numeric', 'gt:0'],
            'less_qty' => ['required_if:amount,0', 'numeric', 'gt:0'],
            'images' => 'required|array',
            'images.*' => ['required', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg', 'max:20000'],
            'cover' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            // 'data' => 'nullable|array',
            // 'data.*.type' => 'required|in:brand,color,wight,size',
            // 'data.*.title' => 'required|string',
            // 'data.*.value' => 'required|array',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => ['nullable', 'array'],
            'subcategory_id.*' => ['nullable', 'numeric',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->join('categories', 'id', 'parent_id');
                }),
            ],
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
            'quantity' => $request->quantity,
            'less_qty' => $request->less_qty,
            'description' => $request->description,
            'purchasing_price' => $request->purchasing_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'cover' => $request->cover,
             'amount' => 1,
            'category_id' => $request->category_id,
            'subcategory_id' => $subcategory,
            'store_id' => null,

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
    public function show($id)
    {
        //
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
        if (is_null($product) || $product->is_deleted == 1) {
            return $this->sendError(" المنتج غير موجود", "product is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            // 'amount' => ['required', 'numeric'],

            'quantity' => ['required_if:amount,0', 'numeric', 'gt:0'],
            'less_qty' => ['required_if:amount,0', 'numeric', 'gt:0'],
            'purchasing_price' => ['required', 'numeric', 'gt:0'],
            'selling_price' => ['required', 'numeric', 'gte:purchasing_price'],
            'stock' => ['required', 'numeric', 'gt:0'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'images' => 'nullable|array',
            'images.*' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg', 'max:20000'],
            // 'data' => 'nullable|array',
            // 'data.*.type' => 'required|in:brand,color,wight,size',
            // 'data.*.title' => 'required|string',
            // 'data.*.value' => 'required|array',
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
            'quantity' => $request->input('quantity'),
            'less_qty' => $request->input('less_qty'),
            'stock' => $request->input('stock'),
            'cover' => $request->cover,
             'amount' => 1,
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $subcategory,

        ]);
        $productid = $product->id;

        if ($request->hasFile("images")) {
            $files = $request->file("images");

            $image_id = Image::where('product_id', $id)->pluck('id')->toArray();
            foreach ($image_id as $oid) {
                $image = Image::query()->find($oid);
                $image->update(['is_deleted' => 1]);

            }

            foreach ($files as $file) {
                $imageName = Str::random(10) . time() . '.' . $file->getClientOriginalExtension();
                $request['product_id'] = $productid;
                $request['image'] = $imageName;
                $filePath = 'images/product/' . $imageName;
                $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($file));
                Image::create($request->all());

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

    public function deleteall(Request $request)
    {

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('for', 'stock')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                $product->update(['is_deleted' => 1]);
                $success['products'] = new ProductResource($product);

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

        if (is_null($product) || $product->is_deleted == 1) {
            return $this->sendError("المنتج غير موجودة", "product is't exists");
        }

        if ($product->for === 'stock') {
            $product->update(['for' => 'etlobha']);
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
            // Log::alert($row['cover']);
            // Log::info($row['cover']);

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

}
