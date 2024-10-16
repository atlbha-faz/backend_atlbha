<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
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
        $store = auth()->user()->store_id;
        if (auth()->user()->store->verification_status == "accept") {

            $etlobha_categories = Category::with(['store' => function ($query) {
                $query->select('id');
            }])->
                where('is_deleted', 0)
                ->where('parent_id', null)
                ->where('store_id', null)
                ->orderByDesc('created_at')->select('id', 'name', 'status', 'icon', 'number', 'store_id', 'parent_id', 'created_at');

            if ($request->has('category_id')) {
                $etlobha_categories->where('id', $request->category_id);
            }
            $etlobha_categories = $etlobha_categories->paginate($count);
            $categories = Category::with(['store' => function ($query) {
                $query->select('id');
            }])->where('is_deleted', 0)
                ->where('parent_id', null)
                ->where('store_id', auth()->user()->store_id)
                ->orderByDesc('created_at')->select('id', 'name', 'status', 'icon', 'number', 'store_id', 'parent_id', 'created_at');
            if ($request->has('category_id')) {
                $categories->where('id', $request->category_id);
            }
            if ($request->has('is_service')) {
                $categories->where('is_service',1);
            }
            else{
                $categories->where('is_service',0);
            }
            $categories = $categories->paginate($count);
            $success['etlobha_page_count'] = $etlobha_categories->lastPage();
            $success['etlobha_current_page'] = $etlobha_categories->currentPage();
            $success['etlobha_categories'] = CategoryResource::collection($etlobha_categories);

            $success['store_page_count'] = $categories->lastPage();
            $success['store_current_page'] = $categories->currentPage();
            $success['store_categories'] = CategoryResource::collection($categories);

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');
        } else {
            $categories = Category::with(['store' => function ($query) {
                $query->select('id');
            }])->
                where('is_deleted', 0)
                ->where('parent_id', null)
                ->where('store_id', auth()->user()->store_id)
                ->orderByDesc('created_at')->select('id', 'name', 'status', 'icon', 'number', 'store_id', 'parent_id', 'created_at');

           
            if ($request->has('category_id')) {
                $categories->where('id', $request->category_id);
            }
            $categories = $categories->paginate($count);
            $success['etlobha_page_count'] = 0;
            $success['etlobha_current_page'] = 0;
            $success['etlobha_categories'] = array();

            $success['store_page_count'] = $categories->lastPage();
            $success['store_current_page'] = $categories->currentPage();
            $success['store_categories'] = CategoryResource::collection($categories);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'icon' => 'nullable',
            'is_service' => 'nullable|in:0,1',
            'data.*.name' => 'nullable|string|max:255',
            'data.*.id' => 'nullable|numeric',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        $cat = Category::orderBy('id', 'desc')->first();
        if (is_null($cat)) {
            $number = 0001;
        } else {

            $number = $cat->number;
            $number = ((int) $number) + 1;
        }

        $category = Category::create([
            'name' => $request->input('name'),
            'number' => str_pad($number, 4, '0', STR_PAD_LEFT),
            'icon' => $request->icon,
            'is_service' =>is_null($request->is_service) ? 0 : $request->is_service,
            'for' => 'store',
            'parent_id' => null,
            'store_id' => auth()->user()->store_id,
        ]);

        if ($request->data) {
            foreach ($request->data as $data) {
                $cat = Category::orderBy('id', 'desc')->first();
                $number = $cat->number;
                $number = ((int) $number) + 1;

                $subcategory = new Category([
                    'name' => $data['name'],
                    'number' => str_pad($number, 4, '0', STR_PAD_LEFT),
                    'is_service' =>is_null($request->is_service) ? 0 : $request->is_service,
                    'parent_id' => $category->id,
                    'store_id' => auth()->user()->store_id,
                ]);

                $subcategory->save();

            }
        }
        $success['categories'] = new CategoryResource($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة التصنيف بنجاح', 'Category Added successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        $category = Category::query()->find($category);
        if (is_null($category) || $category->is_deleted != 0) {
            return $this->sendError("القسم غير موجودة", "Category is't exists");
        }

        $success['categories'] = new CategoryResource($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض القسم بنجاح', 'Category showed successfully');
    }

    public function children($parent)
    {
        $category = Category::where('parent_id', $parent)->where('is_deleted', 0)->get();

        $success['categories'] = new CategoryResource($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاقسام الفرعية بنجاح', 'sub_Category showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    public function changeStatus($id)
    {
        $category = Category::query()->find($id);
        if (is_null($category) || $category->is_deleted != 0 || $category->store_id != auth()->user()->store_id) {
            return $this->sendError("القسم غير موجودة", "category is't exists");
        }
        if ($category->parent_id == null) {
            $categories = Category::where('parent_id', $category->id)->get();
        }

        foreach ($categories as $subcategory) {

            if ($subcategory->status === 'active') {
                $subcategory->update(['status' => 'not_active']);
            } else {
                $subcategory->update(['status' => 'active']);
            }
        }
        if ($category->status === 'active') {
            $category->update(['status' => 'not_active']);
        } else {
            $category->update(['status' => 'active']);
        }
        \Artisan::call('cache:clear');
        $success['categories'] = new CategoryResource($category);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعدبل حالة القسم بنجاح', ' category status updared successfully');

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category)
    {
        $category = Category::where('id', $category)->where('store_id', auth()->user()->store_id)->first();

        if (is_null($category) || $category->is_deleted != 0) {
            return $this->sendError("التصنيف غير موجودة", " Category is't exists");
        }
        // if($request->parent_id == null){
        $category_id = $category->id;

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'icon' => ['nullable'],

            'data.*.name' => 'nullable|string|max:255',
            'data.*.id' => 'nullable|numeric',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $category->update([
            'name' => $request->input('name'),
            'icon' => $request->icon,
        ]);

        $subcategory = Category::where('parent_id', $category_id);

        // dd($request->$data['id']);
        $subcategories_id = Category::where('parent_id', $category_id)->pluck('id')->toArray();
        foreach ($subcategories_id as $oid) {
            if ($request->data != null) {
                if (!(in_array($oid, array_column($request->data, 'id')))) {
                    $subcategory = Category::query()->find($oid);
                    $subcategory->update(['is_deleted' => $subcategory->id]);
                }
            }
        }
        if ($request->data) {
            foreach ($request->data as $data) {
                $sub_cat = Category::find($data['id']);

                if (!is_null($sub_cat)) {
                    $number = $sub_cat->number;
                } else {
                    $cat = Category::orderBy('id', 'desc')->first();
                    $number = $cat->number;
                    $number = ((int) $number) + 1;
                    $number = str_pad($number, 4, '0', STR_PAD_LEFT);
                }

                $subcategories[] = Category::updateOrCreate([
                    'id' => $data['id'],

                ], [
                    'name' => $data['name'],
                    'parent_id' => $category_id,
                    'number' => $number,

                    // 'store_id'=> auth()->user()->store_id,
                    'is_deleted' => 0,

                ]);
            }
        } else {
            $subcategory = Category::where('parent_id', $category_id)->get();
            foreach ($subcategory as $sub) {$sub->delete();}
        }

        $success['categories'] = new CategoryResource($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($category)
    {
        $category = Category::query()->find($category);
        if (is_null($category) || $category->is_deleted != 0 || $category->store_id != auth()->user()->store_id) {
            return $this->sendError("القسم غير موجودة", "category is't exists");
        }
        // check
        $productsCount = Product::where('category_id', $category->id)->where('is_deleted', 0)->count();

        if ($productsCount > 0) {
            return $this->sendError("لا يمكن حذف الانشطة لانها تحتوى على منتجات", "Cannot delete category because it has associated products");
        }

        if ($category->parent_id == null) {
            $categories = Category::where('parent_id', $category->id)->get();

            foreach ($categories as $subcategory) {

                $subcategory->update(['is_deleted' => $subcategory->id]);
            }
        }
        //
        $category->update(['is_deleted' => $category->id]);

        $success['activities'] = new CategoryResource($category);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف القسم بنجاح', 'category deleted successfully');
    }

    public function deleteAll(Request $request)
    {

        $categorys = Category::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        if (count($categorys) > 0) {
            foreach ($categorys as $category) {

                $productsCount = Product::where('category_id', $category->id)->where('is_deleted', 0)->count();

                if ($productsCount > 0) {
                    return $this->sendError("لا يمكن حذف الانشطة لانها تحتوى على منتجات", "Cannot delete category because it has associated products");
                }

                if ($category->parent_id == null) {
                    $categories = Category::where('parent_id', $category->id)->get();

                    foreach ($categories as $subcategory) {

                        $subcategory->update(['is_deleted' => $subcategory->id]);
                    }
                }
                $category->update(['is_deleted' => $category->id]);

                $success['categorys'] = new CategoryResource($category);

            }
            if ($request->has('page')) {
                $categorys = Category::where('store_id', auth()->user()->store_id)->where('parent_id', null)->where('is_deleted', 0)->orderByDesc('created_at')->paginate(5);
                if ($categorys != null) {
                    $success['page_count'] = $categorys->lastPage();
                    $success['coupon_count'] = $categorys->count();
                    $success['current_page'] = $categorys->currentPage();
                    $pageNumber = request()->query('page', 1);
                    $pageItem = $categorys->last();
                    $itemId = $pageItem->id;

                    $categoryLists = Category::where('id', '>=', $itemId)->where('parent_id', null)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->get();

                    $success['categories'] = CategoryResource::collection($categoryLists);
                } else {
                    $success['categories'] = null;
                }
            } else {
                $success['categories'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->get());

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف التصنيف بنجاح', 'category deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'التصنيف غير صحيح', 'category does not exit');
        }
    }
    public function changeSatusAll(Request $request)
    {

        $categorys = Category::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        if (count($categorys) > 0) {
            foreach ($categorys as $category) {

                if ($category->status === 'active') {
                    $category->update(['status' => 'not_active']);
                } else {
                    $category->update(['status' => 'active']);
                }
                if ($category->parent_id == null) {

                    $categories = Category::where('parent_id', $category->id)->get();

                    foreach ($categories as $subcategory) {

                        if ($subcategory->status === 'active') {
                            $subcategory->update(['status' => 'not_active']);
                        } else {
                            $subcategory->update(['status' => 'active']);
                        }
                    }
                }

                $success['categorys'] = new CategoryResource($category);

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل حالة التصنيف بنجاح', 'category updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'التصنيف غير صحيح', 'category does not exit');
        }
    }

    public function deleteItems()
    {
        $categorys = Category::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        if (count($categorys) > 0) {
            foreach ($categorys as $category) {
                $productsCount = Product::where('category_id', $category->id)->where('is_deleted', 0)->count();

                if ($productsCount > 0) {
                    return $this->sendError("لا يمكن حذف الانشطة لانها تحتوى على منتجات", "Cannot delete category because it has associated products");
                }

                $category->update(['is_deleted' => $category->id]);
                $success['categorys'] = new CategoryResource($category);
            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف التصنيف بنجاح', 'category deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'التصنيف غير صحيح', 'category does not exit');
        }
    }
    public function searchCategoryName(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $categories = Category::where('is_deleted', 0)->where('parent_id', null)->where('store_id', auth()->user()->store_id)->where('name', 'like', "%$query%")->orderBy('created_at', 'desc')->select(['id', 'name', 'status', 'icon', 'number', 'store_id', 'parent_id', 'created_at'])
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $categories->total();
        $success['page_count'] = $categories->lastPage();
        $success['current_page'] = $categories->currentPage();
        $success['store_categories'] = CategoryResource::collection($categories);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع التصنيفات بنجاح', 'Categories Information returned successfully');

    }
    public function searchCategoryEtlobha(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $categories = Category::where('is_deleted', 0)->where('parent_id', null)->where('store_id', null)->where('name', 'like', "%$query%")->orderBy('created_at', 'desc')->select(['id', 'name', 'status', 'icon', 'number', 'store_id', 'parent_id', 'created_at'])
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $categories->total();
        $success['page_count'] = $categories->lastPage();
        $success['current_page'] = $categories->currentPage();
        $success['etlobha_categories'] = CategoryResource::collection($categories);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع التصنيفات بنجاح', 'Categories Information returned successfully');

    }

}
