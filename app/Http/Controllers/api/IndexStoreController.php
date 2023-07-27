<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\importsResource;
use App\Http\Resources\MaintenanceResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Homepage;
use App\Models\Importproduct;
use App\Models\Package_store;
use App\Models\Page;
use App\Models\Paymenttype;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Store;
use App\Models\website_socialmedia;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexStoreController extends BaseController
{
    public function index($id)
    {
        if ($id == 'atlbha') {

            // $store = Store::where('is_deleted', 0)->where('id', $id)->first();

            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', null)->pluck('logo')->first();
            $success['domain'] = $id;

            //  $success['logoFooter']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo_footer')->first();
            $sliders = array();
            $sliders[] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('sliderstatus1', 'active')->pluck('slider1')->first();
            $sliders[] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('sliderstatus2', 'active')->pluck('slider2')->first();
            $sliders[] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('sliderstatus3', 'active')->pluck('slider3')->first();
            $success['sliders'] = $sliders;
            $banars = array();
            $banars[] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('banarstatus1', 'active')->pluck('banar1')->first();
            $banars[] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('banarstatus2', 'active')->pluck('banar2')->first();
            $banars[] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('banarstatus3', 'active')->pluck('banar3')->first();
            $success['banars'] = $banars;
            //  $success['blogs']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->get());

            // special products
            $success['specialProducts'] = ProductResource::collection(Product::where('is_deleted', 0)
                    ->where('store_id', null)->where('special', 'special')->orderBy('created_at', 'desc')->get());

            ///////////////////////////
            $success['categoriesHaveSpecial'] = Category::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->with('products')->has('products')->whereHas('products', function ($query) {
                $query->where('is_deleted', 0)->where('special', 'special');
            })->get();
            //
            // more sale

            $arr = array();
            $orders = DB::table('order_items')->where('order_status', 'completed')->join('products', 'order_items.product_id', '=', 'products.id')->where('products.store_id', null)->where('products.is_deleted', 0)
                ->select('products.id', DB::raw('sum(order_items.quantity) as count'))
                ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();

            foreach ($orders as $order) {
                $arr[] = Product::find($order->id);

            }
            $success['moreSales'] = ProductResource::collection($arr);
            // resent arrivede

            $oneWeekAgo = Carbon::now()->subWeek();

            $success['resentArrivede'] = ProductResource::collection(Product::where('is_deleted', 0)
                    ->where('store_id', null)->whereDate('created_at', '>=', $oneWeekAgo)->get());
            ////////////////////////////////////////
            $resent_arrivede_by_category = Category::where('is_deleted', 0)->where('store_id', null)->whereHas('products', function ($query) use ($id) {
                $query->where('is_deleted', 0)->where('store_id', null)->whereDate('created_at', '>=', Carbon::now()->subWeek());
            })->get();

            foreach ($resent_arrivede_by_category as $category) {

                $success['resentArrivedeByCategory'][][$category->name] = ProductResource::collection(Product::where('is_deleted', 0)
                        ->where('store_id', null)->whereDate('created_at', '>=', $oneWeekAgo)->where('category_id', $category->id)->get());
            }

            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->where('postcategory_id', null)->get());
            $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->take(6)->get());
            $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->with('products')->has('products')->get());

            // $arr = array();
            // $offers = DB::table('offers')->where('offers.is_deleted', 0)->where('offers.store_id', null)->join('offers_products', 'offers.id', '=', 'offers_products.offer_id')
            //     ->where('offers.store_id', null)
            //     ->select('offers_products.product_id')
            //     ->groupBy('offers_products.product_id')->get();

            // foreach ($offers as $offer) {
            //     $arr[] = Product::find($offer->product_id);

            // }
            // $success['productsOffers'] = ProductResource::collection($arr);

            $arr = array();
            $orders = DB::table('comments')->where('comments.is_deleted', 0)->where('comments.store_id', null)->join('products', 'comments.product_id', '=', 'products.id')->where('products.is_deleted', 0)
                ->select('products.id', 'comments.rateing')->groupBy('comments.product_id')->orderBy('comments.rateing', 'desc')->take(3)->get();
            foreach ($orders as $order) {$arr[] = Product::find($order->id);}
            $success['productsRatings'] = ProductResource::collection($arr);
            //   $success['productsRatings']=Comment::where('is_deleted',0)->where('store_id',$id)->orderBy('rateing', 'DESC')->with('product')->has('product')->take(3)->get();
            $productsCategories = Product::where('store_id', null)->whereHas('category', function ($query) {
                $query->where('is_deleted', 0)->where('for', 'etlobha');
            })->groupBy('category_id')->selectRaw('count(*) as total, category_id')->orderBy('total', 'DESC')->take(6)->get();

            foreach ($productsCategories as $productsCategory) {
                $success['PopularCategories'][] = new CategoryResource(Category::where('is_deleted', 0)->where('id', $productsCategory->category_id)->first());
            }
            $success['storeName'] = Setting::where('is_deleted', 0)->pluck('name')->first();
            $success['storeEmail'] = Setting::where('is_deleted', 0)->pluck('email')->first();
            $success['storeAddress'] = Setting::where('is_deleted', 0)->pluck('address')->first();
            $success['phonenumber'] = Setting::where('is_deleted', 0)->pluck('phonenumber')->first();
            $success['description'] = Setting::where('is_deleted', 0)->pluck('description')->first();
            $success['snapchat'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->where('status', 'active')->pluck('link')->first();
            $success['facebook'] = website_socialmedia::where('is_deleted', 0)->where('name', 'facebook')->where('status', 'active')->pluck('link')->first();
            $success['twiter'] = website_socialmedia::where('is_deleted', 0)->where('name', 'twitter')->where('status', 'active')->pluck('link')->first();
            // $success['youtube'] =website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->pluck('link')->first();
            $success['instegram'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Instegram')->where('status', 'active')->pluck('link')->first();
            $success['paymentMethod'] = Paymenttype::where('is_deleted', 0)->where('status', 'active')->get();
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم ارجاع الرئيسية للمتجر بنجاح', 'Store index return successfully');

        } else {
            $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();

            if (!is_null($store)) {
                $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
            }
            if (is_null($store) || $store->is_deleted == 1 || is_null($store_package) || $store_package->status == "not_active") {
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

            // $store = Store::where('is_deleted', 0)->where('id', $id)->first();
            if ($store != null) {
                $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->pluck('logo')->first();
                $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();

                //  $success['logoFooter']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo_footer')->first();
                $sliders = array();
                $sliders[] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('sliderstatus1', 'active')->pluck('slider1')->first();
                $sliders[] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('sliderstatus2', 'active')->pluck('slider2')->first();
                $sliders[] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('sliderstatus3', 'active')->pluck('slider3')->first();
                $success['sliders'] = $sliders;
                $banars = array();
                $banars[] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('banarstatus1', 'active')->pluck('banar1')->first();
                $banars[] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('banarstatus2', 'active')->pluck('banar2')->first();
                $banars[] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->where('banarstatus3', 'active')->pluck('banar3')->first();
                $success['banars'] = $banars;
                //  $success['blogs']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->get());

                // special products
                $products = ProductResource::collection(Product::where('is_deleted', 0)->where('special', 'special')->orderBy('created_at', 'desc')->where('store_id', $store_id)->get());

                $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', $store_id)->where('products.special', 'special')->orderBy('products.created_at', 'desc')
                    ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
                $imports = importsResource::collection($import);

                $success['specialProducts'] = $products->merge($imports);

                ///////////////////////////
                $success['categoriesHaveSpecial'] = Category::where('is_deleted', 0)->where('store_id', $id)->with('products')->has('products')->whereHas('products', function ($query) {
                    $query->where('is_deleted', 0)->where('special', 'special');
                })->get();
                //

                // more sale
                $arr1 = array();
                $arr2 = array();
                $orders = DB::table('order_items')->where('order_status', 'completed')->join('products', 'order_items.product_id', '=', 'products.id')->where('order_items.store_id', $store_id)->where('products.is_deleted', 0)
                    ->select('products.id', DB::raw('sum(order_items.quantity) as count'))
                    ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();

                foreach ($orders as $order) {
                    $import = Importproduct::where('product_id', $order->id)->where('store_id', $store_id)->first();
                    if (is_null($import)) {
                        $arr1[] = Product::find($order->id);
                        $products = ProductResource::collection($arr1);
                    } else {
                        $arr2[] = Product::find($order->id);
                        $imports = importsResource::collection($arr2);

                    }

                }
                $success['moreSales'] = $products->merge($imports);
                // resent arrivede

                $oneWeekAgo = Carbon::now()->subWeek();

                $resentimport = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', $store_id)->whereDate('importproducts.created_at', '>=', $oneWeekAgo)
                    ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'purchasing_price', 'store_id']);
                $resentimports = importsResource::collection($resentimport);
                $resentproduct = ProductResource::collection(Product::where('is_deleted', 0)
                        ->where('store_id', $store_id)->whereDate('created_at', '>=', $oneWeekAgo)->get());
                $success['resentArrivede'] = $resentproduct->merge($resentimports);
                ////////////////////////////////////////
                $resent_arrivede_by_category = Category::where('is_deleted', 0)->where('store_id', $store_id)->whereHas('products', function ($query) use ($store_id) {
                    $query->where('is_deleted', 0)->where('store_id', $store_id)->whereDate('created_at', '>=', Carbon::now()->subWeek());
                })->get();

                foreach ($resent_arrivede_by_category as $category) {

                    $success['resentArrivedeByCategory'][][$category->name] = ProductResource::collection(Product::where('is_deleted', 0)
                            ->where('store_id', $store_id)->whereDate('created_at', '>=', $oneWeekAgo)->where('category_id', $category->id)->get());
                }

                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', null)->get());
                $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->take(6)->get());
                $product_ids = Importproduct::where('store_id', $store_id)->pluck('product_id')->toArray();
                $prodtcts = Product::whereIn('id', $product_ids)->where('is_deleted', 0)->groupBy('category_id')->get();
                $category = array();
                foreach ($prodtcts as $prodtct) {
                    $category[] = $prodtct->category;
                }
                $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('store_id', $store_id)->with('products')->has('products')->get()->merge($category));

                // $arr = array();
                // $offers = DB::table('offers')->where('offers.is_deleted', 0)->where('offers.store_id', $id)->join('offers_products', 'offers.id', '=', 'offers_products.offer_id')
                //     ->where('offers.store_id', $id)
                //     ->select('offers_products.product_id')
                //     ->groupBy('offers_products.product_id')->get();

                // foreach ($offers as $offer) {
                //     $arr[] = Product::find($offer->product_id);

                // }
                // $success['productsOffers'] = ProductResource::collection($arr);
                // $success['productsOffers']=Offer::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get();

                $arr = array();
                $orders = DB::table('comments')->where('comments.is_deleted', 0)->where('comments.store_id', $store_id)->join('products', 'comments.product_id', '=', 'products.id')->where('products.is_deleted', 0)
                    ->select('products.id', 'comments.rateing')->groupBy('comments.product_id')->orderBy('comments.rateing', 'desc')->take(3)->get();
                foreach ($orders as $order) {$arr[] = Product::find($order->id);}
                $success['productsRatings'] = ProductResource::collection($arr);
                //   $success['productsRatings']=Comment::where('is_deleted',0)->where('store_id',$id)->orderBy('rateing', 'DESC')->with('product')->has('product')->take(3)->get();
                $productsCategories = Product::where('store_id', $store_id)->whereHas('category', function ($query) {
                    $query->where('is_deleted', 0);
                })->where('is_deleted', 0)->groupBy('category_id')->selectRaw('count(*) as total, category_id')->orderBy('total', 'DESC')->take(6)->get();

                foreach ($productsCategories as $productsCategory) {
                    $success['PopularCategories'][] = new CategoryResource(Category::where('is_deleted', 0)->where('id', $productsCategory->category_id)->first());
                }
                $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
                $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
                $success['storeAddress'] = 'السعودية - مدينة جدة';
                $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('phonenumber')->first();
                $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();
                $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
                $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
                $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
                $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
                $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                $arr = array();
                if ($store->verification_status == 'accept') {
                    if ($store->commercialregistertype == 'maeruf') {
                        $arr['link'] = $store->link;
                        $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                    } else {
                        $arr['link'] = null;
                        $arr['image'] = 'https://backend.atlbha.com/assets/media/commerce.jpeg';
                    }
                    $verificayionMethod = $arr;
                } else {
                    $verificayionMethod = null;
                }
                $success['verificayionMethod'] = $verificayionMethod;
                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع الرئيسية للمتجر بنجاح', 'Store index return successfully');

            } else {

                $success['status'] = 200;

                return $this->sendResponse($success, ' المتجر غير موجود', 'Store is not exists');

            }

        }

    }
    public function productPage($domain, $id)
    {
        if ($domain == 'atlbha') {
            $product = Product::where('is_deleted', 0)->where('id', $id)->where('store_id', null)->first();
            if ($product != null) {
                $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', null)->pluck('logo')->first();
                $success['domain'] = $domain;
                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->where('postcategory_id', null)->get());
                $product = Product::where('is_deleted', 0)->where('id', $id)->where('store_id', null)->first();
                $success['product'] = new ProductResource(Product::where('is_deleted', 0)->where('id', $id)->first());
                $success['relatedProduct'] = ProductResource::collection(Product::where('is_deleted', 0)
                        ->where('store_id', null)->where('category_id', $product->category_id)->whereNotIn('id', [$id])->get());

                $success['commentOfProducts'] = CommentResource::collection(Comment::where('is_deleted', 0)->where('comment_for', 'product')->where('store_id', null)->where('product_id', $product->id)->get());
                $success['storeName'] = Setting::where('is_deleted', 0)->pluck('name')->first();
                $success['storeEmail'] = Setting::where('is_deleted', 0)->pluck('email')->first();
                $success['storeAddress'] = Setting::where('is_deleted', 0)->pluck('address')->first();
                $success['phonenumber'] = Setting::where('is_deleted', 0)->pluck('phonenumber')->first();
                $success['description'] = Setting::where('is_deleted', 0)->pluck('description')->first();
                $success['snapchat'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->pluck('link')->first();
                $success['facebook'] = website_socialmedia::where('is_deleted', 0)->where('name', 'facebook')->pluck('link')->first();
                $success['twiter'] = website_socialmedia::where('is_deleted', 0)->where('name', 'twitter')->pluck('link')->first();
                // $success['youtube'] =website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->pluck('link')->first();
                $success['instegram'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Instegram')->pluck('link')->first();

                //  if(($store->paymenttypes ==null)

                $success['paymentMethod'] = Paymenttype::where('is_deleted', 0)->where('status', 'active')->get();

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع صفحة المنتج للمتجر بنجاح', ' Product page return successfully');

            } else {
                $success['status'] = 200;

                return $this->sendResponse($success, 'المنتج غير موجود', ' Product is not exist');

            }

        } else {
            $store_id = Store::where('domain', $domain)->pluck('id')->first();

            $store = Store::where('id', $store_id)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->first();
            if (!is_null($store)) {
                $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
            }
            if (is_null($store) || $store->is_deleted == 1 || is_null($store_package) || $store_package->status == "not_active") {
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
                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', null)->get());

                $product = Product::where('is_deleted', 0)->where('id', $id)->first();
                $import = Importproduct::where('product_id', $id)->where('store_id', $store_id)->first();
                if ($import != null) {
                    $success['product'] = new importsResource(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('products.id', $id)->first(['products.*', 'importproducts.price']));

                } else {
                    $product = Product::where('is_deleted', 0)->where('store_id', $store_id)->where('id', $id)->first();
                    if ($product != null) {
                        $success['product'] = new ProductResource(Product::where('is_deleted', 0)->where('store_id', $store_id)->where('id', $id)->first());
                    } else {
                        return $this->sendError("المنتج غير موجود", "product is't exists");

                    }
                }
                if ($product != null) {
                    $success['relatedProduct'] = ProductResource::collection(Product::where('is_deleted', 0)
                            ->where('store_id', $store_id)->where('category_id', $product->category_id)->whereNotIn('id', [$id])->get());
                }
                $success['commentOfProducts'] = CommentResource::collection(Comment::where('is_deleted', 0)->where('comment_for', 'product')->where('store_id', $store_id)->where('product_id', $product->id)->get());
                $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
                $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
                $success['storeAddress'] = 'السعودية - مدينة جدة';
                $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('phonenumber')->first();
                $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();

                $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
                $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
                $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
                $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
                $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                //  if(($store->paymenttypes ==null)

                $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();

                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                //dd( $store);
                $arr = array();
                if ($store->verification_status == 'accept') {
                    if ($store->commercialregistertype == 'maeruf') {
                        $arr['link'] = $store->link;
                        $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                    } else {
                        $arr['link'] = null;
                        $arr['image'] = 'https://backend.atlbha.com/assets/media/commerce.jpeg';
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
    }
    public function addComment(Request $request, $id)
    {
        // add comment
        if ($request->domain == 'atlbha') {
            $product = Product::query()->find($id);
            if (is_null($product) || $product->is_deleted == 1) {
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
            $comment = Comment::create([
                'comment_text' => $request->comment_text,
                'rateing' => $request->rateing,
                'comment_for' => 'product',
                'product_id' => $id,
                'store_id' => null,
                'user_id' => auth()->user()->id,

            ]);

            $success['comments'] = new CommentResource($comment);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إضافة تعليق بنجاح', 'comment Added successfully');
        } else {
            $product = Product::query()->find($id);
            if (is_null($product) || $product->is_deleted == 1) {
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

            ]);

            $success['comments'] = new CommentResource($comment);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إضافة تعليق بنجاح', 'comment Added successfully');
        }
    }
    public function storPage(Request $request, $id)
    {

        if ($request->domain == 'atlbha') {
            $page = Page::where('is_deleted', 0)->where('id', $id)->where('store_id', null)->first();
            if ($page != null) {
                $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', null)->pluck('logo')->first();
                $success['domain'] = $request->domain;
                $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->get());
                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->where('postcategory_id', null)->get());
                $page = Page::where('is_deleted', 0)->where('id', $id)->where('store_id', null)->where('postcategory_id', null)->first();
                if ($page != null) {
                    $success['page'] = new PageResource(Page::where('is_deleted', 0)->where('id', $id)->where('store_id', null)->where('postcategory_id', null)->first());
                } else {
                    return $this->sendError("الصفحة غير موجودة", "page is't exists");

                }
                $success['storeName'] = Setting::where('is_deleted', 0)->pluck('name')->first();
                $success['storeEmail'] = Setting::where('is_deleted', 0)->pluck('email')->first();
                $success['storeAddress'] = Setting::where('is_deleted', 0)->pluck('address')->first();
                $success['phonenumber'] = Setting::where('is_deleted', 0)->pluck('phonenumber')->first();
                $success['description'] = Setting::where('is_deleted', 0)->pluck('description')->first();
                $success['snapchat'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->pluck('link')->first();
                $success['facebook'] = website_socialmedia::where('is_deleted', 0)->where('name', 'facebook')->pluck('link')->first();
                $success['twiter'] = website_socialmedia::where('is_deleted', 0)->where('name', 'twitter')->pluck('link')->first();
                // $success['youtube'] =website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->pluck('link')->first();
                $success['instegram'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Instegram')->pluck('link')->first();
                $success['status'] = 200;
                return $this->sendResponse($success, 'تم  الصفحة للمتجر بنجاح', 'Store page return successfully');
            } else {
                $success['status'] = 200;
                return $this->sendResponse($success, 'الصفحة غير موجودة', 'Store page  is not exists');

            }
        } else {
            $store = Store::where('domain', $request->domain)->whereNot('package_id', null)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->first();
            if (!is_null($store)) {
                $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
            }
            if (is_null($store) || $store->is_deleted == 1 || is_null($store_package) || $store_package->status == "not_active") {
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
                $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('store_id', $store_id)->get());
                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', null)->get());
                $success['page'] = new PageResource(Page::where('is_deleted', 0)->where('id', $id)->where('store_id', $store_id)->where('postcategory_id', null)->first());
                $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
                $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
                $success['storeAddress'] = 'السعودية - مدينة جدة';
                $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('phonenumber')->first();
                $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();
                $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
                $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
                $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
                $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
                $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                $arr = array();
                if ($store->verification_status == 'accept') {
                    if ($store->commercialregistertype == 'maeruf') {
                        $arr['link'] = $store->link;
                        $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                    } else {
                        $arr['link'] = null;
                        $arr['image'] = 'https://backend.atlbha.com/assets/media/commerce.jpeg';
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

    }
    public function storeProductCategory(Request $request)
    {

        if ($request->domain == 'atlbha') {

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
                $s = 'id';
            }
            $filter_category = $request->input('filter_category');
            $price_from = $request->input('price_from');
            $price_to = $request->input('price_to');

            $products = ProductResource::collection(Product::where('is_deleted', 0)
                    ->where('store_id', null)->when($filter_category, function ($query, $filter_category) {
                    $query->where('category_id', $filter_category)->orWhere('subcategory_id', $filter_category);
                })->when($price_from, function ($query, $price_from) {
                    $query->where('selling_price', '>=', $price_from);
                })->when($price_to, function ($query, $price_to) {
                    $query->where('selling_price', '<=', $price_to);
                })->orderBy($s, $sort)->paginate($limit));

            $filters = array();
            $filters[0]["items"] = CategoryResource::collection(Category::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->get());
            $filters[0]["name"] = "التصنيفات";
            $filters[0]["slug"] = "category";
            $filters[0]["type"] = "category";
            $filters[0]["value"] = null;
            $filters[1]["max"] = Product::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->orderBy('selling_price', 'desc')->pluck('selling_price')->first();
            $filters[1]["min"] = Product::where('is_deleted', 0)->where('for', 'etlobha')->where('store_id', null)->orderBy('selling_price', 'asc')->pluck('selling_price')->first();
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
            $success['pages'] = $products->lastPage();
            $success['from'] = $products->firstItem();
            $success['to'] = $products->lastItem();
            $success['total'] = $products->total();
            $success['Products'] = $products;
            $success['domain'] = $request->domain;
            $success['lastProducts'] = ProductResource::collection(Product::where('is_deleted', 0)
                    ->where('store_id', null)->where('for', 'etlobha')->orderBy('created_at', 'desc')->take(5)->get());
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم  الصفحة للمتجر بنجاح', 'Store page return successfully');
        } else {

            $store = Store::where('domain', $request->domain)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();
            if (!is_null($store)) {
                $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
            }
            if (is_null($store) || $store->is_deleted == 1 || is_null($store_package) || $store_package->status == "not_active") {
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
            $importsproducts = importsResource::collection(Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)
                    ->where('importproducts.store_id', $store_id)
                    ->whereIn('products.id', $imports_id)
                    ->when($filter_category, function ($query, $filter_category) {
                        $query->where('products.category_id', $filter_category)->orWhere('products.subcategory_id', $filter_category);
                    })->when($price_from, function ($query, $price_from) {
                    $query->where('importproducts.price', '>=', $price_from);
                })->when($price_to, function ($query, $price_to) {
                    $query->where('importproducts.price', '<=', $price_to);
                })->select('products.*', 'importproducts.price')->orderBy($s, $sort)->paginate($limit));
            $storeproducts = ProductResource::collection(Product::where('is_deleted', 0)
                    ->where('store_id', $store_id)->when($filter_category, function ($query, $filter_category) {
                    $query->where('category_id', $filter_category)->orWhere('subcategory_id', $filter_category);
                })->when($price_from, function ($query, $price_from) {
                    $query->where('selling_price', '>=', $price_from);
                })->when($price_to, function ($query, $price_to) {
                    $query->where('selling_price', '<=', $price_to);
                })->orderBy($s, $sort)->paginate($limit));

            $products = $storeproducts->merge($importsproducts);
            $product_ids = Importproduct::where('store_id', $store_id)->pluck('product_id')->toArray();
            $prodtcts = Product::whereIn('id', $product_ids)->where('is_deleted', 0)->groupBy('category_id')->get();
            $category = array();
            foreach ($prodtcts as $prodtct) {
                $category[] = $prodtct->category;
            }
            $filters = array();
            $filters[0]["items"] = CategoryResource::collection(Category::where('is_deleted', 0)->where('for', 'store')
                    ->where(function ($query) use($store_id) {
                        $query->where('store_id', $store_id)
                            ->OrWhere('store_id', null);
                    })->with('products')->has('products')->get())->merge($category);
            $filters[0]["name"] = "التصنيفات";
            $filters[0]["slug"] = "category";
            $filters[0]["type"] = "category";
            $filters[0]["value"] = null;

            $filters[1]["max"] = Product::where('is_deleted', 0)->where('store_id', $store_id)->orderBy('selling_price', 'desc')->pluck('selling_price')->first();
            $filters[1]["min"] = Product::where('is_deleted', 0)->where('store_id', $store_id)->orderBy('selling_price', 'asc')->pluck('selling_price')->first();
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

            //  $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$request->store_id)->pluck('logo')->first();
            //  $success['category']=CategoryResource::collection(Category::where('is_deleted',0)->where('store_id',$request->store_id)->get());
            //  $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->store_id)->where('postcategory_id',null)->get());
            $success['Products'] = $products;

            /*   $success['storeName']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('store_name')->first();
            $success['storeEmail']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('store_email')->first();
            $success['storeAddress']='السعودية - مدينة جدة';
            $success['phonenumber']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('phonenumber')->first();
            $success['description']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('description')->first();
            $success['snapchat']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('snapchat')->first();
            $success['facebook']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('facebook')->first();
            $success['twiter']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('twiter')->first();
            $success['youtube']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('youtube')->first();
            $success['instegram']=Store::where('is_deleted',0)->where('id',$request->store_id)->pluck('instegram')->first();
            $store=Store::where('is_deleted',0)->where('id',$request->store_id)->first();
            $arr=array();
            if($store->verification_status == 'accept'){
            if($store->commercialregistertype == 'maeruf'){
            $arr['link']= $store->link;
            $arr['image']= 'https://backend.atlbha.com/assets/media/maroof.png';
            }
            else{
            $arr['link']= null;
            $arr['image']= 'https://backend.atlbha.com/assets/media/commerce.jpeg';
            }
            $verificayionMethod=$arr;
            }
            else{
            $verificayionMethod =null ;
            }
            $success['verificayionMethod']=$verificayionMethod ;

             */
            $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();
            $success['lastProducts'] = ProductResource::collection(Product::where('is_deleted', 0)
                    ->where('store_id', $store_id)->orderBy('created_at', 'desc')->take(5)->get());
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم  الصفحة للمتجر بنجاح', 'Store page return successfully');

        }

    }

    public function category($id)
    {
        $category = Category::query()->find($id);
        if (is_null($category) || $category->is_deleted == 1) {
            return $this->sendError("القسم غير موجودة", "Category is't exists");
        }
        $success['category'] = new CategoryResource($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض القسم بنجاح', 'Category showed successfully');
    }
    public function productSearch(Request $request)
    {
        if ($request->domain == 'atlbha') {
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
            $products = ProductResource::collection(Product::where('is_deleted', 0)
                    ->where('store_id', null)
                    ->where('for', 'etlobha')
                    ->where('name', 'like', '%' . $query . '%')
                    ->when($category, function ($query, $category) {
                        $query->where('category_id', $category)->orWhere('subcategory_id', $category);
                    })
                    ->get());
            $success['domain'] = $request->domain;
            $success['searchProducts'] = $products;
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم عرض المنتجات بنجاح', 'Category products successfully');

        } else {
            $store = Store::where('domain', $request->domain)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->whereNot('package_id', null)->first();
            if (!is_null($store)) {
                $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
            }
            if (is_null($store) || $store->is_deleted == 1 || is_null($store_package) || $store_package->status == "not_active") {
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
                    }, )
                    ->get(['products.*', 'importproducts.price']));

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

    }

    // المدونه

}
