<?php

namespace App\Http\Controllers\api\storeTemplate;

use App\Helpers\StoreHelper;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\DayResource;
use App\Http\Resources\DaystoreResource;
use App\Http\Resources\ImportsProductSearchResource;
use App\Http\Resources\importsResource;
use App\Http\Resources\MaintenanceResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductStoreResource;
use App\Http\Resources\SeoResource;
use App\Http\Resources\SubscriptionEmailResource;
use App\Http\Resources\TechnicalsupportResource;
use App\Http\Resources\ThemeResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Homepage;
use App\Models\Importproduct;
use App\Models\Page;
use App\Models\Page_page_category;
use App\Models\Product;
use App\Models\Seo;
use App\Models\Store;
use App\Models\SubscriptionEmail;
use App\Models\TechnicalSupport;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexStoreController extends BaseController
{
    public function index($id)
    {
        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        $store->increment('views');

        $success['logo'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('logo')->first();
        $success['icon'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('icon')->first();
        $success['domain'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('domain')->first();
        $theme = Theme::where('store_id', $store->id)->first();
        if ($theme != null) {
            $success['Theme'] = new ThemeResource(Theme::where('store_id', $store->id)->select('id', 'primaryBg', 'secondaryBg', 'headerBg', 'layoutBg', 'iconsBg', 'footerBorder', 'footerBg', 'fontColor')->first());

        }

        /////////////////////////////////////////
        $product_ids = Importproduct::where('store_id', $store->id)->pluck('product_id')->toArray();
        $prodtcts = Product::whereIn('id', $product_ids)->where('is_deleted', 0)->where('status', 'active')->groupBy('category_id')->get();
        $category = array();
        foreach ($prodtcts as $prodtct) {
            $categoryOne = Category::with(['subcategory' => function ($query) use ($prodtct) {
                $query->whereIn('id', $prodtct->subcategory()->pluck('id')->toArray());
            }])->where('is_deleted', 0)->where('id', $prodtct->category_id
            )->where('status', 'active')->first();
            if ($categoryOne !== null) {
                $category[] = $categoryOne;
            }
        }
        ////////////////////////////////////////////////////////////////////
        $originalcategory = array();
        $original_category_first = array();
        $original_category_second = array();
        $originalProdutcts = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->get();
        foreach ($originalProdutcts as $originalProdutct) {
            $mainCategory = Category::with(['subcategory' => function ($query) use ($originalProdutct) {
                $query->whereIn('id', $originalProdutct->subcategory()->pluck('id')->toArray());
            }])->where('is_deleted', 0)->where('id', $originalProdutct->category_id
            )->where('store_id', null)->where('status', 'active')->first();
            if ($mainCategory !== null) {
                if (!empty($originalProdutct->subcategory()->pluck('id')->toArray())) {
                    $original_category_first = array_merge($original_category_first, $originalProdutct->subcategory()->pluck('id')->toArray());
                }

                $original_category_second[] = $mainCategory->id;

            }
        }
        $original_category_first = array_unique($original_category_first);
        $original_category_second = array_unique($original_category_second);

        $lastCategory = Category::with(['subcategory' => function ($query) use ($original_category_first) {
            $query->whereIn('id', $original_category_first);
        }])->where('is_deleted', 0)->whereIn('id', $original_category_second
        )->where('store_id', null)->where('status', 'active')->get();

        $categories = Category::where('is_deleted', 0)->where('status', 'active')->where('parent_id', null)
            ->where('store_id', $store->id)->get()->merge($category)->concat($lastCategory);

        if ($categories != null) {
            $success['categories'] = CategoryResource::collection($categories);
        } else {
            $success['categories'] = array();
        }

        $pages = Page_page_category::where('page_category_id', 2)->pluck('page_id')->toArray();
        $success['pages'] = PageResource::collection(Page::with(['store' => function ($query) {
            $query->select('id');
        }, 'user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->where('postcategory_id', null)->get());

        $success['storeId'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('id')->first();

        $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_name')->first();
        $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_email')->first();
        $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_address')->first();
        $success['phonenumber'] = User::where('is_deleted', 0)->where('store_id', $store->id)->pluck('phonenumber')->first();
        $success['description'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('description')->first();
        $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('snapchat')->first();
        $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('facebook')->first();
        $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('twiter')->first();
        $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('youtube')->first();
        $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('instegram')->first();
        $success['tiktok'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('tiktok')->first();
        $success['jaco'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('jaco')->first();
        $success['verification_code'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('verification_code')->first();

        $store = Store::where('is_deleted', 0)->where('id', $store->id)->first();
        $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();
        $store = Store::where('is_deleted', 0)->where('id', $store->id)->first();
        $verificayion_arr = array();
        if ($store->verification_status == 'accept') {
            if ($store->verification_type == 'maeruf') {
                $verificayion_arr['link'] = 'https://eauthenticate.saudibusiness.gov.sa/inquiry';
                $verificayion_arr['image'] = 'https://backend.atlbha.sa/assets/media/maroof.jpeg';
                $verificayion_arr['type'] = 'maeruf';
            } else {
                $verificayion_arr['link'] = 'https://eauthenticate.saudibusiness.gov.sa/inquiry';
                $verificayion_arr['image'] = 'https://backend.atlbha.sa/assets/media/new_commerce.png';
                $verificayion_arr['type'] = 'commerce';
            }
            $verificayionMethod = $verificayion_arr;
        } else {
            $verificayionMethod = null;
        }
        $success['verificayionMethod'] = $verificayionMethod;

        if ($store->working_status == 'not_active') {
            foreach (\App\Models\Day::get() as $day) {

                $daystore[] = (object) [
                    'day' => new DayResource($day),
                    'from' => '00:00:00',
                    'to' => '12:00:00',
                    'status' => 'active',
                ];

            }
        } else {
            $daystore = $store->daystore;
        }

        $success['workDays'] = DaystoreResource::collection($daystore);

        $seo = Seo::where('is_deleted', 0)->where('store_id', $store->id)->select('id', 'google_analytics', 'snappixel', 'tiktokpixel', 'twitterpixel', 'instapixel', 'key_words', 'metaDescription', 'header', 'footer', 'graph', 'tag', 'search', 'title')->first();
        if ($seo !== null) {
            $success['Seo'] = new SeoResource($seo);
        } else {
            $success['Seo'] = null;
        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الرئيسية للمتجر بنجاح', 'Store index return successfully');

    }

    public function productPage($domain, $id)
    {
        $store = StoreHelper::check_store_existing($domain);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        if ($store->id != null) {
            $success['domain'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('domain')->first();
            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->pluck('logo')->first();
            $success['icon'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('icon')->first();
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->where('postcategory_id', null)->get());

            $product = Product::where('is_deleted', 0)->where('status', 'active')->where('id', $id)->first();
            $import = Importproduct::where('product_id', $id)->where('store_id', $store->id)->first();
            if ($import == null && $product == null) {

                return $this->sendError("المنتج غير موجود", "product is't exists");
            } else {

                $success['product'] = new ProductStoreResource(Product::with(['importproduct' => function ($query) use ($store) {
                    $query->where('store_id', $store->id);
                }])->where('is_deleted', 0)
                        ->where(function ($main_query) use ($store, $id) {
                            $main_query->whereHas('importproduct', function ($productQuery) use ($store, $id) {
                                $productQuery->where('product_id', $id)->where('store_id', $store->id)->where('status', 'active');
                            })->orwhere('store_id', $store->id)->where('id', $id)->where('status', 'active');

                        })->first());

            }
        }
        if ($product != null) {
            $success['relatedProduct'] = ProductResource::collection(Product::with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name', 'store_email', 'logo', 'icon');
            }, 'category'])->where('is_deleted', 0)->where('status', 'active')
                    ->where('store_id', $store->id)->where('category_id', $product->category_id)->whereNotIn('id', [$id])->limit(10)->get());
        }

        $commentStatus = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->where('commentstatus', 'active')->first();

        if ($commentStatus != null) {

            $success['commentOfProducts'] = CommentResource::collection(Comment::where('is_deleted', 0)->where('comment_for', 'product')->where('store_id', $store->id)->where('product_id', $id)->where('status', 'active')->get());
        } else {
            $success['commentOfProducts'] = array();
        }

        $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_name')->first();
        $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_email')->first();
        $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_address')->first();
        $success['phonenumber'] = User::where('is_deleted', 0)->where('store_id', $store->id)->pluck('phonenumber')->first();
        $success['description'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('description')->first();

        $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('snapchat')->first();
        $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('facebook')->first();
        $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('twiter')->first();
        $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('youtube')->first();
        $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('instegram')->first();
        $success['tiktok'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('tiktok')->first();
        $success['jaco'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('jaco')->first();
        $success['verification_code'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('verification_code')->first();
        $store = Store::where('is_deleted', 0)->where('id', $store->id)->first();

        $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();

        $store = Store::where('is_deleted', 0)->where('id', $store->id)->first();

        $arr = array();
        if ($store->verification_status == 'accept') {
            if ($store->verification_type == 'maeruf') {

                $arr['image'] = 'https://backend.atlbha.sa/assets/media/maroof.png';
            } else {
                $arr['link'] = null;
                $arr['image'] = 'https://backend.atlbha.sa/assets/media/new_commerce.png';
            }
            $verificayionMethod = $arr;
        } else {
            $verificayionMethod = null;
        }
        $success['verificayionMethod'] = $verificayionMethod;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع صفحة المنتج للمتجر بنجاح', ' Product page return successfully');

    }
    public function addComment(Request $request, $id)
    {

        $product = Product::query()->find($id);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'comment_text' => 'required|string|max:255',
            'rateing' => 'required|numeric|lte:5',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $store_id = Store::where('domain', $request->domain)->pluck('id')->first();
        $comment = Comment::create([
            'comment_text' => $request->comment_text,
            'rateing' => $request->rateing,
            'comment_for' => 'product',
            'product_id' => $id,
            'store_id' => $store_id,
            'user_id' => auth()->user()->id,
            'status' => 'not_active',
        ]);

        $success['comments'] = new CommentResource($comment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة تعليق بنجاح', 'comment Added successfully');

    }
    public function storPage(Request $request, $id)
    {

        $store = StoreHelper::check_store_existing($request->domain);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }
        $page = Page::where('is_deleted', 0)->where('id', $id)->where('store_id', $store->id)->first();
        if ($page != null) {
            $success['domain'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('domain')->first();
            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->pluck('logo')->first();
            $success['icon'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('icon')->first();
            $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->get());
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store->id)->where('postcategory_id', null)->get());
            $success['page'] = new PageResource(Page::where('is_deleted', 0)->where('id', $id)->where('store_id', $store->id)->where('postcategory_id', null)->first());
            $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_name')->first();
            $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_email')->first();
            $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('store_address')->first();
            $success['phonenumber'] = User::where('is_deleted', 0)->where('store_id', $store->id)->pluck('phonenumber')->first();
            $success['description'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('description')->first();
            $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('snapchat')->first();
            $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('facebook')->first();
            $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('twiter')->first();
            $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('youtube')->first();
            $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('instegram')->first();
            $success['tiktok'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('tiktok')->first();
            $success['jaco'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('jaco')->first();
            $success['verification_code'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('verification_code')->first();
            $store = Store::where('is_deleted', 0)->where('id', $store->id)->first();
            $arr = array();
            if ($store->verification_status == 'accept') {
                if ($store->verification_type == 'maeruf') {
                    $arr['link'] = $store->link;
                    $arr['image'] = 'https://backend.atlbha.sa/assets/media/maroof.png';
                } else {
                    $arr['link'] = null;
                    $arr['image'] = 'https://backend.atlbha.sa/assets/media/new_commerce.png';
                }
                $verificayionMethod = $arr;
            } else {
                $verificayionMethod = null;
            }
            $success['verificayionMethod'] = $verificayionMethod;
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم  الصفحة للمتجر بنجاح', 'Store page return successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'الصفحة غير موجودة', 'Store page  is not exists');
        }

    }
    public function storeProductCategory(Request $request)
    {
        $store = StoreHelper::check_store_existing($request->domain);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'limit' => 'numeric',
            'page' => 'numeric',
            'filter_category' => 'numeric',
            'price_from' => 'numeric',
            'price_to' => 'numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $limit = $request->input('limit');
        if ($limit == null) {
            $limit = 12;
        }
        $page = $request->input('page');
        if ($page == null) {
            $page = 1;
        }
        $sort = $request->input('sort');
        $s = 'name';
        if ($sort == null) {
            $sort = 'desc';
            $s = 'products.id';
        }
        $filter_category = $request->input('filter_category');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');
        $imports_id = Importproduct::where('store_id', $store->id)->pluck('product_id')->toArray();
        if (is_null($filter_category)) {
            $importsproducts = ImportsProductSearchResource::collection(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.status', 'active')
                    ->where('importproducts.store_id', $store->id)
                    ->where('importproducts.status', 'active')
                    ->whereIn('products.id', $imports_id)
                    ->when($price_from, function ($query, $price_from) {
                        $query->where('importproducts.price', '>=', $price_from);
                    })->when($price_to, function ($query, $price_to) {
                    $query->where('importproducts.price', '<=', $price_to);
                })->select('products.*', 'importproducts.qty', 'importproducts.price', 'importproducts.discount_price_import')->orderBy($s, $sort)->paginate($limit));
        } else {
            $importsproducts = ImportsProductSearchResource::collection(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.status', 'active')
                    ->where('importproducts.store_id', $store->id)
                    ->where('importproducts.status', 'active')
                    ->whereIn('products.id', $imports_id)
                    ->where(function ($query) use ($filter_category) {
                        $query->where('products.category_id', $filter_category)->orWhere('products.subcategory_id', $filter_category);
                    })->when($price_from, function ($query, $price_from) {
                    $query->where('importproducts.price', '>=', $price_from);
                })->when($price_to, function ($query, $price_to) {
                    $query->where('importproducts.price', '<=', $price_to);
                })->select('products.*', 'importproducts.qty', 'importproducts.discount_price_import', 'importproducts.price')->orderBy($s, $sort)->paginate($limit));
        }
        if ($request->has('filter_category') && $filter_category != null) {
            $parent = Category::where('is_deleted', 0)->where('status', 'active')
                ->whereNot('parent_id', null)->when($filter_category, function ($query, $filter_category) {
                $query->where('id', $filter_category);
            })->pluck('parent_id')->first();
        } else {
            $parent = null;
        }
        $productssStoreid = array();
        if ($parent !== null) {
            $subProducts = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->where('category_id', $parent)->get();
            foreach ($subProducts as $subProduct) {
                if (strpos($subProduct->subcategory_id, $filter_category) !== false) {

                    $productssStoreid[] = $subProduct->id;
                }
            }
            $store_products = Product::with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name');
            }, 'category' => function ($query) {
                $query->select('id', 'name');
            }])->where('is_deleted', 0)->where('status', 'active')
                ->where('store_id', $store->id)->whereIn('id', $productssStoreid)->when($price_from, function ($query, $price_from) {
                $query->where('selling_price', '>=', $price_from);
            })->when($price_to, function ($query, $price_to) {
                $query->where('selling_price', '<=', $price_to);
            })->where('store_id', $store->id)->where('is_deleted', 0);

            if ($request->has('is_service')) {
                $store_products->where('is_service', 1);
            } else {
                $store_products->where('is_service', 0);
            }
            $storeproducts = ProductResource::collection($store_products->orderBy($s, $sort)->paginate($limit));

        } else {
            $store_products = Product::with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name');
            }, 'category' => function ($query) {
                $query->select('id', 'name');
            }])->where('is_deleted', 0)->where('status', 'active')
                ->where('store_id', $store->id)->when($filter_category, function ($query, $filter_category) use ($parent) {
                $query->where('category_id', $filter_category)->orWhere('category_id', $parent);
            })->when($price_from, function ($query, $price_from) {
                $query->where('selling_price', '>=', $price_from);
            })->when($price_to, function ($query, $price_to) {
                $query->where('selling_price', '<=', $price_to);
            })->where('store_id', $store->id)->where('is_deleted', 0);
            if ($request->has('is_service')) {
                $store_products->where('is_service', 1);
            } else {
                $store_products->where('is_service', 0);
            }

            $storeproducts = ProductResource::collection($store_products->orderBy($s, $sort)->paginate($limit));
        }
        if ($request->has('is_service')) {
            $products = $storeproducts;
        } else {
            $products = $storeproducts->merge($importsproducts);
        }

        $product_ids = Importproduct::where('store_id', $store->id)->pluck('product_id')->toArray();
        $prodtcts = Product::whereIn('id', $product_ids)->where('is_deleted', 0)->where('status', 'active')->groupBy('category_id')->get();
        $category = array();
        foreach ($prodtcts as $prodtct) {
            $category[] = $prodtct->category;
        }
        $adminCategory = Category::where('is_deleted', 0)->where('status', 'active')
            ->Where('store_id', null)->with('products')->has('products')->get();
        $filters = array();
        $categories = Category::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }])->where('is_deleted', 0)->where('status', 'active')->where(function ($query) use ($store) {
            $query->where('store_id', $store->id)
                ->OrWhere('store_id', null)->whereHas('products', function ($query) use ($store) {
                $query->where('is_deleted', 0)->where('store_id', $store->id);
            });
        })->with('products');

        if ($request->has('is_service')) {
            $categories = $categories->where('is_service', 1)->get();
        } else {
            $categories = $categories->where('is_service', 0)->get()->merge($category);
        }
        $filters[0]["items"] = CategoryResource::collection($categories);

        $filters[0]["name"] = "التصنيفات";
        $filters[0]["slug"] = "category";
        $filters[0]["type"] = "category";
        $filters[0]["value"] = null;

        $filters[1]["max"] = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->orderBy('selling_price', 'desc')->pluck('selling_price')->first();
        $filters[1]["min"] = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->orderBy('selling_price', 'asc')->pluck('selling_price')->first();
        $filters[1]["name"] = "السعر";
        $filters[1]["slug"] = "price";
        $filters[1]["type"] = "range";
        $filters[1]["value"] = [$filters[1]["min"], $filters[1]["max"]];

        $success['filters'] = $filters;
        $success['filter_category'] = $filter_category;
        $success['limit'] = $limit;
        $success['page'] = $page;
        $success['sort'] = $sort;
        $success['price_from'] = $price_from;
        $success['price_to'] = $price_to;
        $success['pages'] = (!$request->has('is_service')) ? ($storeproducts->lastPage() < $importsproducts->lastPage() ? $importsproducts->lastPage() : $storeproducts->lastPage()) : $storeproducts->lastPage();

        $success['from'] = (!$request->has('is_service')) ? ($storeproducts->firstItem() == null ? $importsproducts->firstItem() : $storeproducts->firstItem()) : $storeproducts->firstItem();
        $success['to'] = (!$request->has('is_service')) ? (($storeproducts->lastItem() == null ? 0 : $storeproducts->lastItem()) + ($importsproducts->lastItem() == null ? 0 : $importsproducts->lastItem())) : $storeproducts->lastItem();
        $success['total'] = (!$request->has('is_service')) ? (($storeproducts->total() == null ? 0 : $storeproducts->total()) + ($importsproducts->total() == null ? 0 : $importsproducts->total())) : $storeproducts->total();
        $success['Products'] = $products;

        $success['domain'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('domain')->first();
        $product_ids = Importproduct::where('store_id', $store->id)->orderBy('created_at', 'desc')->pluck('product_id')->toArray();
        $importprodtcts = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.status', 'active')->whereIn('products.id', $product_ids)->select('products.*', 'importproducts.qty', 'importproducts.discount_price_import', 'importproducts.price')->orderBy('importproducts.created_at', 'desc')->take(5)->get();
        $last_products = Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }])->where('is_deleted', 0)->where('store_id', $store->id);
        if ($request->has('is_service')) {
            $last_products = $last_products->where('is_service', 1);
        } else {
            $last_products = $last_products->where('is_service', 0);
        }
        if ($products != null) {
            $success['lastProducts'] = ProductResource::collection($last_products->orderBy('created_at', 'desc')->take(5)->get());
        } else {
            $success['lastProducts'] = importsResource::collection($importprodtcts);
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم  الصفحة للمتجر بنجاح', 'Store page return successfully');

    }

    public function category($id)
    {
        $category = Category::query()->find($id);
        if (is_null($category) || $category->is_deleted != 0 || $category->status == 'not_active') {
            return $this->sendError("القسم غير موجودة", "Category is't exists");
        }
        $success['category'] = new CategoryResource($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض القسم بنجاح', 'Category showed successfully');
    }
    public function productSearch(Request $request)
    {
        $store = StoreHelper::check_store_existing($request->domain);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'query' => 'string',
            'category' => 'numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $category = $request->input('category');
        $query = $request->input('query');
        $imports_id = Importproduct::where('store_id', $store->id)->pluck('product_id')->toArray();
        $products = ProductStoreResource::collection(Product::with(['importproduct' => function ($query) use ($store) {
            $query->where('store_id', $store->id);
        }])->where('is_deleted', 0)
                ->where('name', 'like', '%' . $query . '%')
                ->where(function ($query) use ($store) {
                    $query->whereHas('importproduct', function ($productQuery) use ($store) {
                        $productQuery->where('store_id', $store->id)->where('status', 'active');
                    })->orwhere('store_id', $store->id)->where('status', 'active');
                })
                ->when($category, function ($query, $category) {
                    $query->where('category_id', $category)->orWhere('subcategory_id', $category);
                })
                ->get());

        $success['domain'] = Store::where('is_deleted', 0)->where('id', $store->id)->pluck('domain')->first();
        $success['searchProducts'] = $products;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض المنتجات بنجاح', 'Category products successfully');

    }
    public function addContact(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $store_id = Store::where('domain', $request->domain)->pluck('id')->first();
        $support = TechnicalSupport::create([
            'title' => $request->title,
            'content' => $request->content,
            'store_id' => $store_id,
            'user_id' => auth()->user()->id,
            'supportstatus' => 'not_finished',
        ]);

        $success['supports'] = new TechnicalsupportResource($support);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارسال الرسالة بنجاح', 'message sent successfully');
    }
    // المدونه
    public function addSubsicription(Request $request, $domain)
    {
        $store = StoreHelper::check_store_existing($domain);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $store_id = Store::where('domain', $domain)->pluck('id')->first();
        $subsicription = SubscriptionEmail::create([
            'email' => $request->email,
            'store_id' => $store_id,

        ]);

        $success['subsicriptions'] = new SubscriptionEmailResource($subsicription);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم الاشتراك', 'subscription successfully');
    }
    public function specialProducts(Request $request, $id)
    {

        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $specialproducts = Product::with(['importproduct' => function ($query) use ($store) {
            $query->where('store_id', $store->id);
        }])->where('status', 'active')->where('is_deleted', 0)
            ->where(function ($query) use ($store) {
                $query->whereHas('importproduct', function ($productQuery) use ($store) {
                    $productQuery->where('store_id', $store->id)->where('status', 'active')->where('special', 'special');
                })->orwhere('store_id', $store->id)->where('status', 'active')->where('special', 'special');
            })->orderBy('created_at', 'desc')->select('id', 'name', 'status', 'cover', 'special', 'stock', 'selling_price', 'purchasing_price', 'discount_price', 'store_id', 'category_id', 'created_at');
        if ($request->has('category_id')) {
            $specialproducts->where('category_id', $request->category_id);
        }

        $specialproducts = $specialproducts->paginate($count);
        $success['specialProducts'] = ProductStoreResource::collection($specialproducts);
        $success['page_count'] = $specialproducts->lastPage();
        $success['current_page'] = $specialproducts->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات المميزه بنجاح', 'specialProducts show successfully');

    }
    public function recentProducts(Request $request, $id)
    {
        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }
        $store->id = $store->id;

        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $oneWeekAgo = Carbon::now()->subWeek();

        $resentproduct = Product::with(['importproduct' => function ($query) use ($store) {
            $query->where('store_id', $store->id);
        }])->where('status', 'active')->where('is_deleted', 0)
            ->where(function ($query) use ($store) {
                $query->whereHas('importproduct', function ($productQuery) use ($store) {
                    $productQuery->where('store_id', $store->id)->where('status', 'active');
                })->orwhere('store_id', $store->id)->where('status', 'active');

            })->orderBy('created_at', 'desc')->select('id', 'name', 'status', 'cover', 'special', 'stock', 'selling_price', 'purchasing_price', 'discount_price', 'store_id', 'category_id', 'is_service', 'created_at');
        if ($request->has('category_id')) {
            $resentproduct->where('category_id', $request->category_id);
        }
        if ($request->has('is_service')) {
            $resentproduct->where('is_service', 1);
        } else {
            $resentproduct->where('is_service', 0);
        }

        $resentproduct = $resentproduct->paginate($count);
        $success['resent_arrive'] = ProductStoreResource::collection($resentproduct);
        $success['page_count'] = $resentproduct->lastPage();
        $success['current_page'] = $resentproduct->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات الجديده بنجاح', 'newProducts show successfully');

    }
    public function moreSalesProducts(Request $request, $id)
    {

        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        $main_product = array();
        $import_product = array();
        $orders = DB::table('order_items')->where('order_status', 'completed')->join('products', 'order_items.product_id', '=', 'products.id')->where('order_items.store_id', $store->id)->where('products.is_deleted', 0)
            ->select('products.id', DB::raw('sum(order_items.quantity) as count'))
            ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();
        $moreSalesImports = array();
        foreach ($orders as $order) {
            $import = Importproduct::where('product_id', $order->id)->where('store_id', $store->id)->first();
            if (!is_null($import)) {

                $import_product[] = $import->product_id;
            } else {
                $main_product[] = $order->id;
            }
        }

        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $moreSalesProducts = Product::with(['importproduct' => function ($query) use ($store) {
            $query->where('store_id', $store->id);
        }])->where('status', 'active')->where('is_deleted', 0)
            ->where(function ($query) use ($store, $import_product, $main_product) {
                $query->whereHas('importproduct', function ($productQuery) use ($store, $import_product, $main_product) {
                    $productQuery->where('store_id', $store->id)->whereIn('product_id', $import_product)->where('status', 'active');
                })->orwhere('store_id', $store->id)->whereIn('id', $main_product)->where('status', 'active');

            })->orderBy('created_at', 'desc')->select('id', 'name', 'status', 'cover', 'special', 'stock', 'selling_price', 'purchasing_price', 'discount_price', 'store_id', 'category_id', 'created_at');
        if ($request->has('category_id')) {
            $moreSalesProducts->where('category_id', $request->category_id);
        }

        $moreSalesProducts = $moreSalesProducts->paginate($count);
        $success['moreSalesProducts'] = ProductStoreResource::collection($moreSalesProducts);
        $success['page_count'] = $moreSalesProducts->lastPage();
        $success['current_page'] = $moreSalesProducts->currentPage();
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع المنتجات الاكثر طلبا بنجاح', 'specialProducts show successfully');

    }
    public function productsRatings(Request $request, $id)
    {

        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }
        $arr = array();
        $ratingsimport = array();
        $ratings = DB::table('comments')->where('comments.is_deleted', 0)->where('comments.store_id', $store->id)->join('products', 'comments.product_id', '=', 'products.id')->where('products.is_deleted', 0)
            ->select('products.id', 'comments.rateing')->groupBy('comments.product_id')->orderBy('comments.rateing', 'desc')->take(3)->get();

        foreach ($ratings as $rating) {
            $importing = Importproduct::where('product_id', $rating->id)->where('store_id', $store->id)->first();
            if (!is_null($importing)) {
                $ratingsimport[] = $importing->product_id;

            } else {
                $arr[] = $rating->id;
            }
        }

        if ($store != null) {

            $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

            $resentproduct = Product::with(['importproduct' => function ($query) use ($store) {
                $query->where('store_id', $store->id);
            }])->where('status', 'active')->where('is_deleted', 0)
                ->where(function ($query) use ($store, $ratingsimport, $arr) {
                    $query->whereHas('importproduct', function ($productQuery) use ($store, $ratingsimport, $arr) {
                        $productQuery->where('store_id', $store->id)->whereIn('product_id', $ratingsimport)->where('status', 'active');
                    })->orwhere('store_id', $store->id)->whereIn('id', $arr)->where('status', 'active');

                })->orderBy('created_at', 'desc')->select('id', 'name', 'status', 'cover', 'special', 'stock', 'selling_price', 'purchasing_price', 'discount_price', 'store_id', 'category_id', 'created_at');
            if ($request->has('category_id')) {
                $resentproduct->where('category_id', $request->category_id);
            }
            $resentproduct = $resentproduct->paginate($count);
            $success['ratingsProducts'] = ProductStoreResource::collection($resentproduct);
            $success['page_count'] = $resentproduct->lastPage();
            $success['current_page'] = $resentproduct->currentPage();
            $success['status'] = 200;
        }

        return $this->sendResponse($success, 'تم ارجاع المنتجات  الاكثر تقييما بنجاح', 'ratingsProducts show successfully');
    }
    public function lastPosts(Request $request, $id)
    {
        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }
        $posts = Page_page_category::where('page_category_id', 1)->pluck('page_id')->toArray();

        $success['lastPosts'] = PageResource::collection(Page::with(['store' => function ($query) {
            $query->select('id');
        }, 'user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->where('status', 'active')->where('store_id', $store->id)->whereIn('id', $posts)->orderBy('created_at', 'desc')->take(6)->get());

        return $this->sendResponse($success, 'تم ارجاع المقالات بنجاح', 'posts show successfully');
    }
    public function silders(Request $request, $id)
    {
        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        $sliders = array();
        $s1 = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->where('sliderstatus1', 'active')->pluck('slider1')->first();
        if (!is_null($s1)) {
            $sliders[] = $s1;
        }
        $s2 = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->where('sliderstatus2', 'active')->pluck('slider2')->first();
        if (!is_null($s2)) {
            $sliders[] = $s2;
        }
        $s3 = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->where('sliderstatus3', 'active')->pluck('slider3')->first();
        if (!is_null($s3)) {
            $sliders[] = $s3;
        }
        $success['sliders'] = $sliders;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع السيالدر بنجاح', 'sliders show successfully');

    }
    public function banars(Request $request, $id)
    {
        $store = StoreHelper::check_store_existing($id);
        if ($store) {
            if ($store->maintenance != null) {
                if ($store->maintenance->status == 'active') {
                    $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);
                    $success['status'] = 200;
                    return $this->static::sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
                }
            }
        } else {
            return $this->sendError("  المتجر غير موجود", "store is't exists");
        }

        $banars = array();
        $b1 = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->where('banarstatus1', 'active')->pluck('banar1')->first();
        if (!is_null($b1)) {
            $banars[] = $b1;
        }
        $b2 = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->where('banarstatus2', 'active')->pluck('banar2')->first();
        if (!is_null($b2)) {
            $banars[] = $b2;
        }
        $b3 = Homepage::where('is_deleted', 0)->where('store_id', $store->id)->where('banarstatus3', 'active')->pluck('banar3')->first();
        if (!is_null($b3)) {
            $banars[] = $b3;
        }
        $success['banars'] = $banars;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع البانرات بنجاح', 'banars show successfully');

    }

}
