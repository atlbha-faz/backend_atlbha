<?php

namespace App\Http\Controllers\api;

use App\Models\Page;
use App\Models\Store;
use App\Models\Category;
use App\Models\Homepage;
use App\Models\Postcategory;
use Illuminate\Http\Request;
use App\Models\Page_page_category;
use App\Http\Resources\PageResource;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\api\BaseController as BaseController;

class PostStoreController extends BaseController
{
   public function index($id){
       $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo')->first();
      $success['category']=Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get();
         $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id',null)->get());
        $success['posts']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->orderBy('created_at', 'desc')->get());
        $success['postCategory']=Postcategory::where('is_deleted',0)->get();
        $success['lastPosts']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->orderBy('created_at', 'desc')->take(3)->get());
        // footer
         $success['storeName']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_name')->first();
         $success['storeEmail']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_email')->first();
         $success['phonenumber']=Store::where('is_deleted',0)->where('id',$id)->pluck('phonenumber')->first();
         $success['description']=Store::where('is_deleted',0)->where('id',$id)->pluck('description')->first();

         $success['snapchat']=Store::where('is_deleted',0)->where('id',$id)->pluck('snapchat')->first();
         $success['facebook']=Store::where('is_deleted',0)->where('id',$id)->pluck('facebook')->first();
         $success['twiter']=Store::where('is_deleted',0)->where('id',$id)->pluck('twiter')->first();
         $success['youtube']=Store::where('is_deleted',0)->where('id',$id)->pluck('youtube')->first();
         $success['instegram']=Store::where('is_deleted',0)->where('id',$id)->pluck('instegram')->first();
         $store=Store::where('is_deleted',0)->where('id',$id)->first();
         $success['paymentMethod']=$store->paymenttypes->where('status','active');
        return $this->sendResponse($success,'تم ارجاع المدونة بنجاح','posts return successfully');
    }
    public function show($postCategory_id,Request $request){
        $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$request->id)->pluck('logo')->first();
        $success['category']=Category::where('is_deleted',0)->where('store_id',$request->id)->with('products')->has('products')->get();
       $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->id)->where('postcategory_id',null)->get());
        $success['posts']= PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->id)->where('postcategory_id',$postCategory_id)->get());
        // $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
        $success['postCategory']=Postcategory::where('is_deleted',0)->get();
        $success['lastPosts']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->id)->where('postcategory_id','!=',null)->orderBy('created_at', 'desc')->take(3)->get());
        // footer
         $success['storeName']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('store_name')->first();
         $success['storeEmail']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('store_email')->first();
         $success['phonenumber']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('phonenumber')->first();
         $success['description']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('description')->first();

         $success['snapchat']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('snapchat')->first();
         $success['facebook']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('facebook')->first();
         $success['twiter']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('twiter')->first();
         $success['youtube']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('youtube')->first();
         $success['instegram']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('instegram')->first();
         $store=Store::where('is_deleted',0)->where('id',$request->id)->first();
         $success['paymentMethod']=$store->paymenttypes->where('status','active');
        return $this->sendResponse($success,'تم ارجاع الصفحة بنجاح',' post return successfully');
    }
    public function show_post($pageId,Request $request){

            $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$request->id)->pluck('logo')->first();
        $success['category']=CategoryResource::collection(Category::where('is_deleted',0)->where('store_id',$request->id)->with('products')->has('products')->get());
     $success['post']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->id)->where('postcategory_id','!=',null)->where('id',$pageId)->get());
        $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->id)->where('postcategory_id',null)->get());
        // $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
        $success['postCategory']=Postcategory::where('is_deleted',0)->get();
         $success['lastPosts']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->id)->where('postcategory_id','!=',null)->orderBy('created_at', 'desc')->take(3)->get());
        // $success['footer']=PageResource::collection(Page::where('is_deleted',0)->whereIn('id',$pages)->get());
        // footer
         $success['storeName']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('store_name')->first();
         $success['storeEmail']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('store_email')->first();
         $success['phonenumber']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('phonenumber')->first();
         $success['description']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('description')->first();

         $success['snapchat']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('snapchat')->first();
         $success['facebook']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('facebook')->first();
         $success['twiter']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('twiter')->first();
         $success['youtube']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('youtube')->first();
         $success['instegram']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('instegram')->first();
         $store=Store::where('is_deleted',0)->where('id',$request->id)->first();
         $success['paymentMethod']=$store->paymenttypes->where('status','active');
        return $this->sendResponse($success,'تم ارجاع الصفحة بنجاح',' post return successfully');
    }
}
