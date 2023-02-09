<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\City;
use App\Models\Country;
use App\Models\Package;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\PackageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class SelectorController extends BaseController
{


    public function __construct()
    {
        $this->middleware('auth:api');
    }

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

}
