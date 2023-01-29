<?php

namespace App\Http\Controllers\api;

use App\Models\Page;
use App\Models\Package;
use App\Models\Homepage;
use App\Models\Postcategory;
use Illuminate\Http\Request;
use App\Models\Page_page_category;
use App\Models\website_socialmedia;
use App\Http\Resources\PageResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\website_socialmediaResource;
use App\Http\Controllers\api\BaseController as BaseController;

class SubpageController extends BaseController
{
  
    public function show($page_id)
    {
        $page= Page::query()->find($page_id);
        if (is_null($page) || $page->is_deleted==1){
               return $this->sendError("الصفحة غير موجودة","Page is't exists");
               }
              $success['pages']=New PageResource($page);
              $success['logo']=Homepage::where('is_deleted',0)->where('store_id',null)->pluck('logo')->first();
              $success['website_socialmedia']=website_socialmediaResource::collection(website_socialmedia::where('is_deleted',0)->where('status','active')->get());
              $categories= Page_page_category::where('page_id',$page->id)->get();
              $categories_id=array();
              foreach($categories as $category)
              {
               
                  $categories_id[]=$category->page_category_id;
              }
            
          
              $pages_id =Page_page_category::whereIn('page_category_id',$categories_id)->pluck('page_id')->toArray();
                  $pages=Page::whereIn('id',$pages_id)->where('id','!=',$page_id)->get();
             $success['Relatedpages']=  PageResource::collection($pages);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الصفحة بنجاح','Page showed successfully');
    }

    public function packages()
    {
       $success['packages']=PackageResource::collection(Package::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الباقات بنجاح','packages return successfully');
    }


}
