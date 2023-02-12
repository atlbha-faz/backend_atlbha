<?php

namespace App\Http\Controllers\api;

use App\Models\Page;
use App\Models\Store;
use App\Models\Comment;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Product;
use App\Models\City;
use App\Models\Activity;
use App\Models\Section;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Models\Page_page_category;
use App\Models\website_socialmedia;
use App\Http\Resources\PageResource;

use App\Http\Resources\CityResource;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\HomepageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\website_socialmediaResource;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexEtlobhaController extends BaseController
{
   public function index(){
     $success['logo']=Homepage::where('is_deleted',0)->where('store_id',null)->pluck('logo')->first();
     $success['logo_footer']=Homepage::where('is_deleted',0)->where('store_id',null)->pluck('logo_footer')->first();
     $success['slider1']=Homepage::where('is_deleted',0)->where('store_id',null)->where('sliderstatus1','active')->pluck('slider1')->first();
     $success['slider2']=Homepage::where('is_deleted',0)->where('store_id',null)->where('sliderstatus2','active')->pluck('slider2')->first();
     $success['slider3']=Homepage::where('is_deleted',0)->where('store_id',null)->where('sliderstatus3','active')->pluck('slider3')->first();

     $success['banar1']=Homepage::where('is_deleted',0)->where('store_id',null)->where('banarstatus1','active')->pluck('banar1')->first();
     $success['banar2']=Homepage::where('is_deleted',0)->where('store_id',null)->where('banarstatus2','active')->pluck('banar2')->first();
     $success['banar3']=Homepage::where('is_deleted',0)->where('store_id',null)->where('banarstatus3','active')->pluck('banar3')->first();

       
        $success['store_activities']=ActivityResource::collection(Activity::where('is_deleted',0)->where('status','active')->get());
       
        $success['cities']=CityResource::collection(City::where('is_deleted',0)->where('status','active')->get());
       
       
     if(Section::where('id',1)->where('is_deleted',0)->where('status','active')){
     $success['section1']=Section::where('id',1)->pluck('name')->first();
     $success['products']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',null)->where('special','special')->get());
    }
    if(Section::where('id',2)->where('is_deleted',0)->where('status','active')){
     $success['section2']=Section::where('id',2)->pluck('name')->first();
     $success['stores']=StoreResource::collection(Store::where('is_deleted',0)->where('special','special')->get());}

     $success['packages']=PackageResource::collection(Package::where('is_deleted',0)->get());

     $success['comment']=CommentResource::collection(Comment::where('is_deleted',0)->where('comment_for','store')->where('store_id',null)->where('product_id',null)->latest()->take(10)->get());
     $success['partners']=PartnerResource::collection(Partner::where('is_deleted',0)->get());

      $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
     $success['footer']=PageResource::collection(Page::where('is_deleted',0)->whereIn('id',$pages)->get());
    $success['website_socialmedia']=website_socialmediaResource::collection(website_socialmedia::where('is_deleted',0)->where('status','active')->get());

        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الرئيسية بنجاح','etlobha index return successfully');
   }
}
