<?php

namespace App\Http\Controllers\api\homePages;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\Page_page_category;
use App\Models\Postcategory;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $post_page = Page::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])->where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc');
        if ($request->has('post_category_id')) {
            $post_page->where('postcategory_id', $request->input('post_category_id'));
        }
        $post_page = $post_page->paginate($count);
        $success['pages'] = PageResource::collection($post_page);
        $success['total_result'] = $post_page->total();
        $success['page_count'] = $post_page->lastPage();
        $success['current_page'] = $post_page->currentPage();
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
    public function show($postCategory_id, Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $postCategory_check = Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', $postCategory_id)->get();
        if ($postCategory_check != null) {

            $category_pages = Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', $postCategory_id)->paginate($count);
            $success['page_count'] = $category_pages->lastPage();
            $success['current_page'] = $category_pages->currentPage();
            $success['pages'] = PageResource::collection($category_pages);
            $pages = Page_page_category::where('page_category_id', 1)->pluck('page_id')->toArray();
            $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع الصفحات بنجاح', 'postcategory is return ');

        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, ' نصنيف الصفحة غير موجود', 'postcategory is not exists');

        }
    }
    public function showPost($id)
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
    public function searchPost(Request $request)
    {
        $searchTerm = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $pages = Page::where('is_deleted', 0)->where('status', 'active')->where('store_id', null)->where('postcategory_id', '!=', null)
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%$searchTerm%")
                    ->orWhere('page_desc', 'LIKE', "%$searchTerm%")
                    ->orWhere('page_content', 'LIKE', "%$searchTerm%");

            })->orderBy('created_at', 'desc');
        if ($request->has('post_category_id')) {
            $pages->where('postcategory_id', $request->input('post_category_id'));
        }
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
