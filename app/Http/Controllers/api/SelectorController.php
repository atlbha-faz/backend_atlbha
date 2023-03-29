<?php

namespace App\Http\Controllers\api;

use App\Models\City;
use App\Models\Plan;
use App\Models\Unit;
use App\Models\Video;
use App\Models\Country;
use App\Models\Package;
use App\Models\Activity;
use App\Models\Template;
use App\Models\Postcategory;
use Illuminate\Http\Request;
use App\Models\Page_category;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\PlanResource;
use App\Http\Resources\UnitResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\TemplateResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostCategoryResource;
use App\Http\Resources\Page_categoryResource;
use App\Http\Controllers\api\BaseController as BaseController;

class SelectorController extends BaseController
{


    public function cities()
    {
        $success['cities']=CityResource::collection(City::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المدن بنجاح','cities return successfully');
    }

  public function countries()
    {
        $success['countries']=CountryResource::collection(Country::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الدول بنجاح','countries return successfully');
    }


  public function activities()
    {
        $success['activities']=ActivityResource::collection(Activity::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الأنشطة بنجاح','activities return successfully');
    }


  public function packages()
    {
        $success['packages']=PackageResource::collection(Package::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الباقات بنجاح','packages return successfully');
    }


 public function addToCart()
    {
      $success['contents']=CartResource::collection(\Cart::content());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الباقات بنجاح','packages return successfully');
    }



}
