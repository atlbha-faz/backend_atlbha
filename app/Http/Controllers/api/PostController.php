<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\Page_page_category;
use App\Models\Postcategory;

class PostController extends BaseController
{
    public function index()
    {

        $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->get());
        $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
        $pages = Page_page_category::where('page_category_id', 1)->pluck('page_id')->toArray();
        
        return $this->sendResponse($success, 'تم ارجاع المدونة بنجاح', 'posts return successfully');
    }
    public function start()
    {

        //مقالات كيف ابدأ
        $startpages = Page_page_category::where('page_category_id', 2)->pluck('page_id')->toArray();
        $success['start'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->where('status', 'active')->whereIn('id', $startpages)->get());
        // $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
        $success['footer'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->select('id', 'title', 'status', 'created_at')->where('status', 'active')->whereIn('id', $startpages)->get());
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع صفحة كيف ابدأ بنجاح', 'start index return successfully');
    }
    public function show($postCategory_id)
    {
        $postCategory_id = Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', $postCategory_id)->first();
        if ($postCategory_id != null) {
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', $postCategory_id)->get());
            $pages = Page_page_category::where('page_category_id', 1)->pluck('page_id')->toArray();
            $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
           
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, ' نصنيف الصفحة غير موجود', 'postcategory is not exists');

        }
    }
    public function show_post($id)
    {
        $post = Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', '!=', null)->where('id', $id)->first();
        if ($post != null) {
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', '!=', null)->where('id', $id)->get());
            $pages = Page_page_category::where('page_category_id', 1)->pluck('page_id')->toArray();
            $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
            return $this->sendResponse($success, 'تم ارجاع الصفحة بنجاح', ' post return successfully');
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, '  المدونه غير موجود', 'post is not exists');

        }
    }
}
