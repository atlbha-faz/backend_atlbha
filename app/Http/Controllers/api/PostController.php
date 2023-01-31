<?php

namespace App\Http\Controllers\api;

use App\Models\Page;
use App\Models\Postcategory;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use App\Models\Page_page_category;
use App\Http\Controllers\api\BaseController as BaseController;

class PostController extends BaseController
{
    public function index(){

        $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',null)->where('postcategory_id','!=',null)->orderBy('created_at', 'desc')->get());
        $success['postCategory']=Postcategory::where('is_deleted',0)->get();
        $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
        $success['footer']=PageResource::collection(Page::where('is_deleted',0)->whereIn('id',$pages)->get());
        return $this->sendResponse($success,'تم ارجاع المدونة بنجاح','posts return successfully');
    }
    public function show($postCategory_id){

        $success['pages']= PageResource::collection(Page::where('is_deleted',0)->where('store_id',null)->where('postcategory_id',$postCategory_id)->get());
        $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
        $success['footer']=PageResource::collection(Page::where('is_deleted',0)->whereIn('id',$pages)->get());
        return $this->sendResponse($success,'تم ارجاع الصفحة بنجاح',' post return successfully');
    }
    public function show_post($id){
        $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',null)->where('postcategory_id','!=',null)->where('id',$id)->get());
        $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
        $success['footer']=PageResource::collection(Page::where('is_deleted',0)->whereIn('id',$pages)->get());
        return $this->sendResponse($success,'تم ارجاع الصفحة بنجاح',' post return successfully');
    }
}
