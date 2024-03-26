<?php

namespace App\Http\Controllers\api\storeTemplate;

use DB;
use Carbon\Carbon;
use App\Models\Seo;
use App\Models\Page;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Theme;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Models\Importproduct;
use App\Models\Package_store;
use App\Models\TechnicalSupport;
use App\Models\SubscriptionEmail;
use App\Models\Page_page_category;
use App\Services\FatoorahServices;
use App\Http\Resources\DayResource;
use App\Http\Resources\SeoResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ThemeResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\importsResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DaystoreResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MaintenanceResource;
use App\Http\Resources\TechnicalsupportResource;
use App\Http\Resources\SubscriptionEmailResource;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexStoreController extends BaseController
{
    public function index($id)
    {
      
        $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();

        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجود", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
            }
        }

        $store_id = $store->id;
        if ($store != null) {
            $success['logo'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('logo')->first();
            $success['icon'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('icon')->first();
            $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();
            $theme = Theme::where('store_id', $store_id)->first();
            if ($theme != null) {
                $success['Theme'] = new ThemeResource(Theme::where('store_id', $store_id)->select('id', 'primaryBg', 'secondaryBg', 'headerBg', 'layoutBg', 'iconsBg', 'footerBorder', 'footerBg', 'fontColor')->first());
            }
            //  $success['logoFooter']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo_footer')->first();
            $sliders = array();
            $s1 = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('sliderstatus1', 'active')->pluck('slider1')->first();
            if (!is_null($s1)) {
                $sliders[] = $s1;
            }
            $s2 = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('sliderstatus2', 'active')->pluck('slider2')->first();
            if (!is_null($s2)) {
                $sliders[] = $s2;
            }
            $s3 = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('sliderstatus3', 'active')->pluck('slider3')->first();
            if (!is_null($s3)) {
                $sliders[] = $s3;
            }
            $success['sliders'] = $sliders;
            $banars = array();
            $b1 = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('banarstatus1', 'active')->pluck('banar1')->first();
            if (!is_null($b1)) {
                $banars[] = $b1;
            }
            $b2 = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('banarstatus2', 'active')->pluck('banar2')->first();
            if (!is_null($b2)) {
                $banars[] = $b2;
            }
            $b3 = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('banarstatus3', 'active')->pluck('banar3')->first();
            if (!is_null($b3)) {
                $banars[] = $b3;
            }
            $success['banars'] = $banars;
            //  $success['blogs']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->get());

            // special products
            $specialproducts = ProductResource::collection(Product::where('is_deleted', 0)->where('status', 'active')->where('special', 'special')->orderBy('created_at', 'desc')->where('store_id', $store_id)->select('id', 'name', 'status', 'cover', 'special', 'stock', 'selling_price', 'purchasing_price', 'discount_price', 'created_at')->get());

            $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', $store_id)->where('importproducts.special', 'special')->orderBy('products.created_at', 'desc')
                ->get(['products.*', 'importproducts.special', 'importproducts.discount_price_import', 'importproducts.price', 'importproducts.qty', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
            $imports = importsResource::collection($import);

            $success['specialProducts'] = $specialproducts->merge($imports);

            ///////////////////////////
            $success['categoriesHaveSpecial'] = Category::where('is_deleted', 0)->where('status', 'active')->where('store_id', $id)->with('products')->has('products')->whereHas('products', function ($query) {
                $query->where('is_deleted', 0)->where('special', 'special');
            })->get();
            //

            // more sale
            $arr1 = array();
            $arr2 = array();
            $orders = DB::table('order_items')->where('order_status', 'completed')->join('products', 'order_items.product_id', '=', 'products.id')->where('order_items.store_id', $store_id)->where('products.is_deleted', 0)
                ->select('products.id', DB::raw('sum(order_items.quantity) as count'))
                ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();
            $moreSalesImports = array();
            foreach ($orders as $order) {
                $import = Importproduct::where('product_id', $order->id)->where('store_id', $store_id)->first();
                if (!is_null($import)) {
                    $arr2[] = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.id', $order->id)->where('products.is_deleted', 0)->where('importproducts.store_id', $store_id)
                        ->first(['products.*', 'importproducts.price', 'importproducts.discount_price_import', 'importproducts.qty', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
                    $moreSalesImports = importsResource::collection($arr2);
                } else {
                    $arr1[] = Product::with(['store' => function ($query) {
                        $query->select('id', 'domain', 'store_name');
                    }, 'category' => function ($query) {
                        $query->select('id', 'name');
                    }])->where('id', $order->id)->select('id', 'name', 'status', 'cover', 'special', 'stock', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'stock')->first();
                }
            }
            $products = ProductResource::collection($arr1);
            $success['moreSales'] = $products->merge($moreSalesImports);
            // resent arrivede

            $oneWeekAgo = Carbon::now()->subWeek();

            $resentimport = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.status', 'active')->where('importproducts.store_id', $store_id)->whereDate('importproducts.created_at', '>=', $oneWeekAgo)
                ->get(['products.*', 'importproducts.price', 'importproducts.discount_price_import', 'importproducts.qty', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'purchasing_price', 'store_id']);
            $resentimports = importsResource::collection($resentimport);
            $resentproduct = ProductResource::collection(Product::with([
                'category' => function ($query) {
                    $query->select('id', 'name');
                },
            ])->where('is_deleted', 0)->select('id', 'name', 'status', 'stock', 'cover', 'special', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'stock', 'discount_price')->where('status', 'active')
                    ->where('store_id', $store_id)->orderByDesc('created_at')->get());
            $success['resentArrivede'] = $resentproduct->merge($resentimports);
            ////////////////////////////////////////
   
            $pages = Page_page_category::where('page_category_id', 2)->pluck('page_id')->toArray();
            $success['pages'] = PageResource::collection(Page::with(['store' => function ($query) {
                $query->select('id');
            }, 'user' => function ($query) {
                $query->select('id');
            }])
                    ->where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('postcategory_id', null)->get());
            $posts = Page_page_category::where('page_category_id', 1)->pluck('page_id')->toArray();

            $success['lastPosts'] = PageResource::collection(Page::with(['store' => function ($query) {
                $query->select('id');
            }, 'user' => function ($query) {
                $query->select('id');
            }])->where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->whereIn('id', $posts)->orderBy('created_at', 'desc')->take(6)->get());
            $product_ids = Importproduct::where('store_id', $store_id)->pluck('product_id')->toArray();
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
            $originalcategory1 = array();
            $originalcategory2= array();
            $originalProdutcts = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->get();
            foreach ($originalProdutcts as $originalProdutct) {
                $mainCategory = Category::with(['subcategory' => function ($query) use ($originalProdutct) {
                    $query->whereIn('id', $originalProdutct->subcategory()->pluck('id')->toArray());
                }])->where('is_deleted', 0)->where('id', $originalProdutct->category_id
                )->where('store_id', null)->where('status', 'active')->first();
                if ($mainCategory !== null) {
                    if( !empty($originalProdutct->subcategory()->pluck('id')->toArray())){
                    $originalcategory1=array_merge($originalcategory1,$originalProdutct->subcategory()->pluck('id')->toArray());
                    }
    
                    $originalcategory2[] = $mainCategory->id;
                    
                }
            }
            
            $originalcategory1=array_unique($originalcategory1);
            $originalcategory2=array_unique($originalcategory2);

            $lastCategory = Category::with(['subcategory' => function ($query) use ($originalcategory1) {
                $query->whereIn('id', $originalcategory1);
            }])->where('is_deleted', 0)->where('id', $originalcategory2
            )->where('store_id', null)->where('status', 'active')->get();
      
            $categories = Category::where('is_deleted', 0)->where('status', 'active')->where('parent_id', null)
                ->where('store_id', $store_id)->get()->merge($category)->concat( $lastCategory);
           
            if ($categories != null) {
                $success['category'] = CategoryResource::collection($categories);
            } else {
                $success['category'] = array();
            }

            

            $arr = array();
            $ratingsimport = array();
            $ratingsImports = array();
            $ratings = DB::table('comments')->where('comments.is_deleted', 0)->where('comments.store_id', $store_id)->join('products', 'comments.product_id', '=', 'products.id')->where('products.is_deleted', 0)
                ->select('products.id', 'comments.rateing')->groupBy('comments.product_id')->orderBy('comments.rateing', 'desc')->take(3)->get();

            foreach ($ratings as $rating) {
                $importing = Importproduct::where('product_id', $rating->id)->where('store_id', $store_id)->first();
                if (!is_null($importing)) {
                    $ratingsimport[] = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.id', $rating->id)->where('products.is_deleted', 0)->where('importproducts.store_id', $store_id)
                        ->first(['products.*', 'importproducts.qty', 'importproducts.price', 'importproducts.discount_price_import', 'importproducts.status'])->makeHidden(['selling_price', 'store_id']);
                    $ratingsImports = importsResource::collection($ratingsimport);
                } else {
                    $arr[] = Product::where('id', $rating->id)->select('id', 'name', 'status', 'cover', 'stock', 'special', 'selling_price', 'purchasing_price', 'discount_price', 'status', 'created_at')->first();
                }
            }

            $success['productsRatings'] = ProductResource::collection($arr)->merge($ratingsImports);

            $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
            $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
            $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_address')->first();
            $success['phonenumber'] = User::where('is_deleted', 0)->where('store_id', $store_id)->pluck('phonenumber')->first();
            $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();
            $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
            $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
            $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
            $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
            $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
            $success['tiktok'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('tiktok')->first();
            $success['jaco'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('jaco')->first();
            $success['verification_code'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('verification_code')->first();

            $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
            $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();
            $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
            $verificayion_arr = array();
            if ($store->verification_status == 'accept') {
                if ($store->verification_type == 'maeruf') {
                    $verificayion_arr['link'] = 'https://eauthenticate.saudibusiness.gov.sa/inquiry';
                    $verificayion_arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.jpeg';
                    $verificayion_arr['type'] = 'maeruf';
                } else {
                    $verificayion_arr['link'] = 'https://eauthenticate.saudibusiness.gov.sa/inquiry';
                    $verificayion_arr['image'] = 'https://backend.atlbha.com/assets/media/new_commerce.png';
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

            $seo = Seo::where('is_deleted', 0)->where('store_id', $store_id)->select('id', 'google_analytics', 'snappixel', 'tiktokpixel', 'twitterpixel', 'instapixel', 'robot_link', 'key_words')->first();
            if ($seo !== null) {
                $success['Seo'] = new SeoResource($seo);
            } else {
                $success['Seo'] = null;
            }
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع الرئيسية للمتجر بنجاح', 'Store index return successfully');
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'Store is not exists');
        }

      

    }

    public function productPage($domain, $id)
    {
       
        $store_id = Store::where('domain', $domain)->pluck('id')->first();

        $store = Store::where('id', $store_id)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->first();
        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
            }
        }

        if ($store_id != null) {
            $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();
            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->pluck('logo')->first();
            $success['icon'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('icon')->first();
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('postcategory_id', null)->get());

            $product = Product::where('is_deleted', 0)->where('status', 'active')->where('id', $id)->first();
            $import = Importproduct::where('product_id', $id)->where('store_id', $store_id)->first();
            if ($import != null) {
                $success['product'] = new importsResource(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', $store_id)->where('products.status', 'active')->where('products.id', $id)->first(['products.*', 'importproducts.qty', 'importproducts.discount_price_import', 'importproducts.price']));
            } else {
                $product = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('id', $id)->first();
                if ($product != null) {
                    $success['product'] = new ProductResource(Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('id', $id)->first());
                } else {
                    return $this->sendError("المنتج غير موجود", "product is't exists");
                }
            }
            if ($product != null) {
                $success['relatedProduct'] = ProductResource::collection(Product::where('is_deleted', 0)->where('status', 'active')
                        ->where('store_id', $store_id)->where('category_id', $product->category_id)->whereNotIn('id', [$id])->get());
            }

            $commentStatus = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('commentstatus', 'active')->first();

            if ($commentStatus != null) {

                $success['commentOfProducts'] = CommentResource::collection(Comment::where('is_deleted', 0)->where('comment_for', 'product')->where('store_id', $store_id)->where('product_id', $id)->where('status', 'active')->get());
            } else {
                $success['commentOfProducts'] = array();
            }

            $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
            $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
            $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_address')->first();
            $success['phonenumber'] = User::where('is_deleted', 0)->where('store_id', $store_id)->pluck('phonenumber')->first();
            $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();

            $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
            $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
            $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
            $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
            $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
            $success['tiktok'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('tiktok')->first();
            $success['jaco'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('jaco')->first();
            $success['verification_code'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('verification_code')->first();
            $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
     

            $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();

            $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
   
            $arr = array();
            if ($store->verification_status == 'accept') {
                if ($store->verification_type == 'maeruf') {
               
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                } else {
                    $arr['link'] = null;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/new_commerce.png';
                }
                $verificayionMethod = $arr;
            } else {
                $verificayionMethod = null;
            }
            $success['verificayionMethod'] = $verificayionMethod;
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع صفحة المنتج للمتجر بنجاح', ' Product page return successfully');
        } else {
            $success['status'] = 200;

            return $this->sendResponse($success, 'صفحة المنتج غير موجود ', ' Product page is not exists');
        }

      
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

       
        $store = Store::where('domain', $request->domain)->whereNot('package_id', null)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->first();
        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
            }
        }

        $store_id = $store->id;
        $page = Page::where('is_deleted', 0)->where('id', $id)->where('store_id', $store_id)->first();
        if ($page != null) {
            $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();
            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->pluck('logo')->first();
            $success['icon'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('icon')->first();
            $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->get());
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', null)->get());
            $success['page'] = new PageResource(Page::where('is_deleted', 0)->where('id', $id)->where('store_id', $store_id)->where('postcategory_id', null)->first());
            $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
            $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
            $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_address')->first();
            $success['phonenumber'] = User::where('is_deleted', 0)->where('store_id', $store_id)->pluck('phonenumber')->first();
            $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();
            $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
            $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
            $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
            $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
            $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
            $success['tiktok'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('tiktok')->first();
            $success['jaco'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('jaco')->first();
            $success['verification_code'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('verification_code')->first();
            $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
            $arr = array();
            if ($store->verification_status == 'accept') {
                if ($store->verification_type == 'maeruf') {
                    $arr['link'] = $store->link;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                } else {
                    $arr['link'] = null;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/new_commerce.png';
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

        $store = Store::where('domain', $request->domain)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();
        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
            }
        }

        $store_id = $store->id;

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
        $imports_id = Importproduct::where('store_id', $store_id)->pluck('product_id')->toArray();
        if (is_null($filter_category)) {
            $importsproducts = importsResource::collection(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.status', 'active')
                    ->where('importproducts.store_id', $store_id)
                    ->whereIn('products.id', $imports_id)
                    ->when($price_from, function ($query, $price_from) {
                        $query->where('importproducts.price', '>=', $price_from);
                    })->when($price_to, function ($query, $price_to) {
                    $query->where('importproducts.price', '<=', $price_to);
                })->select('products.*', 'importproducts.qty', 'importproducts.price', 'importproducts.discount_price_import')->orderBy($s, $sort)->paginate($limit));
        } else {
            $importsproducts = importsResource::collection(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.status', 'active')
                    ->where('importproducts.store_id', $store_id)
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
        $productssStoreid=array();
        if(  $parent !== null){
            $subProducts=Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('category_id', $parent)->get();
            foreach( $subProducts as  $subProduct){
                if(strpos($subProduct->subcategory_id,$filter_category) !== false){

                    $productssStoreid[]= $subProduct->id;
                }
            }
            $storeproducts = ProductResource::collection(Product::with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name');
            }, 'category' => function ($query) {
                $query->select('id', 'name');
            }])->where('is_deleted', 0)->where('status', 'active')
                    ->where('store_id', $store_id)->whereIn('id',$productssStoreid)->when($price_from, function ($query, $price_from) {
                    $query->where('selling_price', '>=', $price_from);
                })->when($price_to, function ($query, $price_to) {
                    $query->where('selling_price', '<=', $price_to);
                })->where('store_id', $store_id)->where('is_deleted', 0)->orderBy($s, $sort)->paginate($limit));
        
        }
        else{
        $storeproducts = ProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');
        }])->where('is_deleted', 0)->where('status', 'active')
                ->where('store_id', $store_id)->when($filter_category, function ($query, $filter_category) use ($parent) {
                $query->where('category_id', $filter_category)->orWhere('category_id', $parent);
            })->when($price_from, function ($query, $price_from) {
                $query->where('selling_price', '>=', $price_from);
            })->when($price_to, function ($query, $price_to) {
                $query->where('selling_price', '<=', $price_to);
            })->where('store_id', $store_id)->where('is_deleted', 0)->orderBy($s, $sort)->paginate($limit));
        }
        $products = $storeproducts->merge($importsproducts);
        $product_ids = Importproduct::where('store_id', $store_id)->pluck('product_id')->toArray();
        $prodtcts = Product::whereIn('id', $product_ids)->where('is_deleted', 0)->where('status', 'active')->groupBy('category_id')->get();
        $category = array();
        foreach ($prodtcts as $prodtct) {
            $category[] = $prodtct->category;
        }
        $adminCategory = Category::where('is_deleted', 0)->where('status', 'active')
            ->Where('store_id', null)->with('products')->has('products')->get();
        $filters = array();
        $filters[0]["items"] = CategoryResource::collection(Category::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }])->where('is_deleted', 0)->where('status', 'active')->where(function ($query) use ($store_id) {
            $query->where('store_id', $store_id)
                ->OrWhere('store_id', null)->whereHas('products', function ($query) use ($store_id) {
                $query->where('is_deleted', 0)->where('store_id', $store_id);
            });
        })->with('products')->get()->merge($category));

        $filters[0]["name"] = "التصنيفات";
        $filters[0]["slug"] = "category";
        $filters[0]["type"] = "category";
        $filters[0]["value"] = null;

        $filters[1]["max"] = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->orderBy('selling_price', 'desc')->pluck('selling_price')->first();
        $filters[1]["min"] = Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->orderBy('selling_price', 'asc')->pluck('selling_price')->first();
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
        $success['pages'] = ($storeproducts->lastPage() < $importsproducts->lastPage() ? $importsproducts->lastPage() : $storeproducts->lastPage());

        $success['from'] = ($storeproducts->firstItem() == null ? $importsproducts->firstItem() : $storeproducts->firstItem());
        $success['to'] = ($storeproducts->lastItem() == null ? 0 : $storeproducts->lastItem()) + ($importsproducts->lastItem() == null ? 0 : $importsproducts->lastItem());
        $success['total'] = ($storeproducts->total() == null ? 0 : $storeproducts->total()) + ($importsproducts->total() == null ? 0 : $importsproducts->total());


        $success['Products'] = $products;

    
        $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();
        $product_ids = Importproduct::where('store_id', $store_id)->orderBy('created_at', 'desc')->pluck('product_id')->toArray();
        $importprodtcts = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.status', 'active')->whereIn('products.id', $product_ids)->select('products.*', 'importproducts.qty', 'importproducts.discount_price_import', 'importproducts.price')->orderBy('importproducts.created_at', 'desc')->take(5)->get();
        $products = Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }])->where('is_deleted', 0)->where('store_id', $store_id)->orderBy('created_at', 'desc')->take(5)->get();
        if ($products != null) {
            $success['lastProducts'] = ProductResource::collection($products);
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
       
        $store = Store::where('domain', $request->domain)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->whereNot('package_id', null)->first();
        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
            }
        }

        $store_id = $store->id;
        $input = $request->all();
        $validator = Validator::make($input, [
            'query' => 'string',
            'category' => 'numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $store_id = $store->id;
        $category = $request->input('category');
        $query = $request->input('query');
        $imports_id = Importproduct::where('store_id', $store_id)->pluck('product_id')->toArray();
        $imports_products = importsResource::collection(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)
                ->where('importproducts.store_id', $store->id)
                ->whereIn('products.id', $imports_id)
                ->where('products.name', 'like', '%' . $query . '%')
                ->when($category, function ($query, $category) {
                    $query->where('products.category_id', $category)->orWhere('products.subcategory_id', $category);
                })
                ->get(['products.*', 'importproducts.qty', 'importproducts.discount_price_import', 'importproducts.price']));

        $products = ProductResource::collection(Product::where('is_deleted', 0)
                ->where('store_id', $store_id)
                ->where('name', 'like', '%' . $query . '%')
                ->when($category, function ($query, $category) {
                    $query->where('category_id', $category)->orWhere('subcategory_id', $category);
                })
                ->get());

        $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();
        $success['searchProducts'] = $products->merge($imports_products);
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
        $store = Store::where('domain', $domain)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();

        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
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
//     public function specialProducts($id)
//     {

//         $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();

//         if (!is_null($store)) {
//             $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
//         }
//         if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
//             return $this->sendError("المتجر غير موجود", "Store is't exists");
//         }
//         if ($store->maintenance != null) {
//             if ($store->maintenance->status == 'active') {
//                 $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

//                 $success['status'] = 200;

//                 return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
//             }
//         }

//         if ($store != null) {
//             if($id =="")
//         $count= ($request->has('number') && $request->input('number') !== null)? $request->input('number'):10;

//         $specialproducts = ProductResource::collection(Product::where('is_deleted', 0)->where('status', 'active')->where('special', 'special')->orderBy('created_at', 'desc')->where('store_id', $store_id)->select('id', 'name', 'status', 'cover', 'special', 'stock', 'selling_price', 'purchasing_price', 'discount_price', 'created_at')->paginate($count));

//         $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', $store_id)->where('importproducts.special', 'special')->orderBy('products.created_at', 'desc')
//             ->get(['products.*', 'importproducts.special', 'importproducts.discount_price_import', 'importproducts.price', 'importproducts.qty', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
//         $imports = importsResource::collection($import);

//         $success['specialProducts'] = $specialproducts;
//         $success['page_count'] = $specialproducts->lastPage();
//         $success['current_page'] = $specialproducts->currentPage();
//         $success['status'] = 200;

//         return $this->sendResponse($success, 'تم ارجاع المنتجات المميزه بنجاح', 'specialProducts show successfully');
//     } 
// }

}
