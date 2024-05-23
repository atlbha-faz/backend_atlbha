<?php

namespace App\Http\Controllers\api\storeTemplate;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MaintenanceResource;
use App\Http\Resources\PageResource;
use App\Models\Category;
use App\Models\Homepage;
use App\Models\Package_store;
use App\Models\Page;
use App\Models\Page_page_category;
use App\Models\Postcategory;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostStoreController extends BaseController
{
    public function index(Request $request,$id)
    {
       
            $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->whereNot('package_id', null)->first();
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

            $id = $store->id;
            if ($store != null) {
                $success['domain'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('domain')->first();
                $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $id)->pluck('logo')->first();
                $success['icon'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('icon')->first();

                $tagarr = array();
                $tags = Page::where('is_deleted', 0)->where('store_id', $id)->pluck('tags')->toArray();
                //    dd($tags);
                foreach ($tags as $tag) {
                    $tagarr[] = $tag;
                }
                $success['tags'] = $tagarr;
                $success['category'] = Category::where('is_deleted', 0)->where('store_id', $id)->with('products')->has('products')->get();
                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $id)->where('postcategory_id', null)->get());
                $postIds = Page_page_category::where('page_category_id', 1)->pluck('page_id')->toArray();
                $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
                $data=Page::whereIn('id',$postIds)->where('is_deleted', 0)->where('status', 'active')->where('store_id', $id)->orderBy('created_at', 'desc');
                $data=  $data->paginate( $count);
                $success['posts'] = PageResource::collection($data);
                $success['page_count'] = $data->lastPage();
                $success['current_page'] = $data->currentPage();
                // $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
                $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $id)->whereIn('id',$postIds)->orderBy('created_at', 'desc')->take(3)->get());
                // footer
                $success['storeName'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('store_name')->first();
                $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('store_email')->first();
                $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('store_address')->first();
                $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('phonenumber')->first();
                $success['description'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('description')->first();

                $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('snapchat')->first();
                $success['facebook'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('facebook')->first();
                $success['twiter'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('twiter')->first();
                $success['youtube'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('youtube')->first();
                $success['instegram'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('instegram')->first();
                $success['tiktok'] = Store::where('is_deleted', 0)->where('id',  $id)->pluck('tiktok')->first();
                $success['jaco'] = Store::where('is_deleted', 0)->where('id',  $id)->pluck('jaco')->first();
                $success['verification_code'] = Store::where('is_deleted', 0)->where('id',  $id)->pluck('verification_code')->first();
                $store = Store::where('is_deleted', 0)->where('id', $id)->first();
                $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();

                $store = Store::where('is_deleted', 0)->where('id', $id)->first();
                $arr = array();
                if ($store->verification_status == 'accept') {
                    if ($store->verification_type == 'maeruf') {
                        // $arr['link'] = $store->link;
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

                return $this->sendResponse($success, 'تم ارجاع المدونة بنجاح', 'posts return successfully');
            } else {

                $success['status'] = 200;

                return $this->sendResponse($success, ' المتجر غير موجود', 'Store is not exists');

            }
        // }

    }
    public function show($postCategory_id, Request $request)
    {
        // if ($request->domain == 'atlbha') {

        //     $postcategory = Page::where('is_deleted', 0)->where('store_id', null)->where('postcategory_id', $postCategory_id)->first();
        //     if ($postcategory != null) {
        //         $success['domain'] = $request->domain;

        //         $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', null)->pluck('logo')->toArray();
        //         $success['icon'] = Setting::where('is_deleted', 0)->pluck('icon')->first();

        //         $tagarr = array();
        //         $tags = Page::where('is_deleted', 0)->where('store_id', null)->where('postcategory_id', '!=', null)->pluck('tags')->toArray();
        //         //    dd($tags);
        //         foreach ($tags as $tag) {
        //             $tagarr[] = $tag;
        //         }
        //         $success['tags'] = $tagarr;

        //         $success['category'] = Category::where('is_deleted', 0)->where('store_id', null)->with('products')->has('products')->get();
        //         $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', null)->get());
        //         $success['posts'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', $postCategory_id)->get());
        //         // $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
        //         $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
        //         $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->take(3)->get());
        //         // footer
        //         $success['storeName'] = Setting::where('is_deleted', 0)->pluck('name')->first();
        //         $success['storeEmail'] = Setting::where('is_deleted', 0)->pluck('email')->first();
        //         $success['storeAddress'] = Setting::where('is_deleted', 0)->pluck('address')->first();
        //         $success['phonenumber'] = Setting::where('is_deleted', 0)->pluck('phonenumber')->first();
        //         $success['description'] = Setting::where('is_deleted', 0)->pluck('description')->first();
        //         $success['snapchat'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->pluck('link')->first();
        //         $success['facebook'] = website_socialmedia::where('is_deleted', 0)->where('name', 'facebook')->pluck('link')->first();
        //         $success['twiter'] = website_socialmedia::where('is_deleted', 0)->where('name', 'twitter')->pluck('link')->first();
        //         // $success['youtube'] =website_socialmedia::where('is_deleted', 0)->where('name', 'Snapchat')->pluck('link')->first();
        //         $success['instegram'] = website_socialmedia::where('is_deleted', 0)->where('name', 'Instegram')->pluck('link')->first();

        //         $success['paymentMethod'] = Paymenttype::where('is_deleted', 0)->where('status', 'active')->get();

        //         return $this->sendResponse($success, 'تم ارجاع الصفحة بنجاح', ' post return successfully');
        //     } else {

        //         $success['status'] = 200;

        //         return $this->sendResponse($success, ' نصنيف الصفحة غير موجود', 'postcategory is not exists');

        //     }

        // } else {
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
            $postcategory = Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', $postCategory_id)->first();
            if ($postcategory != null) {
                $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();

                $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->pluck('logo')->toArray();
                $tagarr = array();
                $tags = Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', '!=', null)->pluck('tags')->toArray();
                $success['icon'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('icon')->first();
                //    dd($tags);
                foreach ($tags as $tag) {
                    $tagarr[] = $tag;
                }
                $success['tags'] = $tagarr;

                $success['category'] = Category::where('is_deleted', 0)->where('store_id', $store_id)->with('products')->has('products')->get();
                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('postcategory_id', null)->get());
                $success['posts'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('postcategory_id', $postCategory_id)->get());
                // $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
                $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
                $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->take(3)->get());
                // footer
                $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
                $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
                $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_address')->first();
                $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('phonenumber')->first();
                $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();

                $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
                $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
                $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
                $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
                $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
                $success['tiktok'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('tiktok')->first();
                $success['jaco'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('jaco')->first();
                $success['verification_code'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('verification_code')->first();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
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
                return $this->sendResponse($success, 'تم ارجاع الصفحة بنجاح', ' post return successfully');
            } else {

                $success['status'] = 200;

                return $this->sendResponse($success, ' نصنيف الصفحة غير موجود', 'postcategory is not exists');

            }
        // }
    }
    public function showPost($pageId, Request $request)
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
            $post = Page::where('is_deleted', 0)->where('store_id', $store_id)->where('id', $pageId)->first();
            if ($post != null) {
                $success['domain'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('domain')->first();
                $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->pluck('logo')->first();
                $success['icon'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('icon')->first();

                $tagarr = array();
                $tags = Page::where('is_deleted', 0)->where('store_id', $store_id)->pluck('tags')->toArray();
                //    dd($tags);
                foreach ($tags as $tag) {
                    $tagarr[] = $tag;
                }
                $success['tags'] = $tagarr;
                $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('store_id', $store_id)->with('products')->has('products')->get());
                $success['post'] = new PageResource(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('id', $pageId)->first());
                $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->where('postcategory_id', null)->get());
                $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
                $cats = Page_page_category::where('page_category_id',1)->pluck('page_id')->toArray();
                $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->whereIn('id',$cats)->whereNot('id', $pageId)->orderBy('created_at', 'desc')->take(3)->get());
                $success['relatedPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $store_id)->whereIn('id',$cats)->whereNot('id', $pageId)->orderBy('created_at', 'desc')->take(2)->get());

                $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
                $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
                $success['storeAddress'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_address')->first();
                $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('phonenumber')->first();
                $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();

                $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
                $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
                $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
                $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
                $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
                $success['tiktok'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('tiktok')->first();
                $success['jaco'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('jaco')->first();
                $success['verification_code'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('verification_code')->first();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                $success['paymentMethod'] = $store->paymenttypes()->where('status', 'active')->get();
                $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
                $arr = array();
                if ($store->verification_status == 'accept') {
                    if ($store->verification_type == 'maeruf') {
                        // $arr['link'] = $store->link;
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
                return $this->sendResponse($success, 'تم ارجاع الصفحة بنجاح', ' post return successfully');
            } else {

                $success['status'] = 200;

                return $this->sendResponse($success, '  المدونه غير موجود', 'post is not exists');

            }
        // }
    }
    public function searchPost(Request $request,$id)
    {
        $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereDate('end_at', '>', Carbon::now())->whereNot('package_id', null)->first();
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

        $id = $store->id;
        $searchTerm = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $pages = Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', $id)
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%$searchTerm%")
                    ->orWhere('page_desc', 'LIKE', "%$searchTerm%")
                    ->orWhere('page_content', 'LIKE', "%$searchTerm%");

            })->orderBy('created_at', 'desc');
     
        $pages = $pages->paginate($count);
        $success['query'] = $searchTerm;
        $success['total_result'] = $pages->total();
        $success['page_count'] = $pages->lastPage();
        $success['current_page'] = $pages->currentPage();
        $success['pages'] = PageResource::collection($pages);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المقالات بنجاح', 'posts returned successfully');
    }
}
