<?php

namespace App\Http\Controllers\api;

use App\Models\Page;
use App\Models\Store;
use App\Models\Comment;
use App\Models\Package;
use App\Models\Product;
use App\Models\Section;
use App\Models\Homepage;
use App\Models\Page_page_category;
use Illuminate\Http\Request;
use App\Models\website_socialmedia;
use App\Http\Resources\StoreResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\HomepageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\website_socialmediaResource;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PageResource;

class IndexEtlobhaController extends BaseController
{
   public function index(){
     $success['logo']=Homepage::where('is_deleted',0)->where('store_id',null)->pluck('logo')->first();
     $success['slider1']=Homepage::where('is_deleted',0)->where('store_id',null)->where('sliderstatus1','active')->pluck('slider1')->first();
     $success['slider2']=Homepage::where('is_deleted',0)->where('store_id',null)->where('sliderstatus2','active')->pluck('slider2')->first();
     $success['slider3']=Homepage::where('is_deleted',0)->where('store_id',null)->where('sliderstatus3','active')->pluck('slider3')->first();

     $success['panar1']=Homepage::where('is_deleted',0)->where('store_id',null)->where('panarstatus1','active')->pluck('slider1')->first();
     $success['panar2']=Homepage::where('is_deleted',0)->where('store_id',null)->where('panarstatus2','active')->pluck('slider2')->first();
     $success['panar3']=Homepage::where('is_deleted',0)->where('store_id',null)->where('panarstatus3','active')->pluck('slider3')->first();

     if(Section::where('id',1)->where('is_deleted',0)->where('status','active')){
     $success['section1']=Section::pluck('name')->first();
     $success['products']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',null)->where('special','special')->get());
    }
    if(Section::where('id',2)->where('is_deleted',0)->where('status','active')){
     $success['section2']=Section::pluck('name')->first();
     $success['stores']=StoreResource::collection(Store::where('is_deleted',0)->where('special','special')->get());}

     $success['packages']=PackageResource::collection(Package::where('is_deleted',0)->get());

     $success['comment']=CommentResource::collection(Comment::where('is_deleted',0)->where('comment_for','store')->where('store_id',null)->where('product_id',null)->latest()->take(2)->get());

      $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
     $success['footer']=PageResource::collection(Page::where('is_deleted',0)->whereIn('id',$pages)->get());
    $success['website_socialmedia']=website_socialmediaResource::collection(website_socialmedia::where('is_deleted',0)->where('status','active')->get());

        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الرئيسية بنجاح','etlobha index return successfully');
   }
}
