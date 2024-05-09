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
        $page = Page::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])->where('id', $page_id)->select('id', 'title', 'page_content', 'altImage', 'default_page', 'page_desc', 'seo_title', 'seo_link', 'seo_desc', 'tags', 'status', 'image')->first();
        if (is_null($page) || $page->is_deleted != 0) {
            return $this->sendError("الصفحة غير موجودة", "Page is't exists");
        }
        $success['pages'] = new PageResource($page);
        
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الصفحة بنجاح', 'Page showed successfully');
    }


    public function packages()
    {
       $success['packages']=PackageResource::collection(Package::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الباقات بنجاح','packages return successfully');
    }


}
