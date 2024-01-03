<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\City;
use App\Models\Plan;
use App\Models\Unit;
use App\Models\Video;
use App\Models\Country;
use App\Models\Category;
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
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\api\BaseController as BaseController;

class SelectorController extends BaseController
{


    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function etlobahCategory()
    {
        $success['categories'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('status','active')->where('parent_id', null)->where('store_id', null)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');
    }

     public function years()
    {
        $success['years']=[2023,2022,2021,2020,2019,2018];
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع السنوات بنجاح','years return successfully');
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

      // $success['activities']=ActivityResource::collection(Activity::where('is_deleted',0)->where('status','active')->get());
      $success['activities']=CategoryResource::collection(Category::where('is_deleted', 0)->where('parent_id', null)->where('store_id', null)->orderByDesc('created_at')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الأنشطة بنجاح','activities return successfully');
    }


  public function packages()
    {
        $success['packages']=PackageResource::collection(Package::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الباقات بنجاح','packages return successfully');
    }


  public function plans()
    {
        $success['plans']=PlanResource::collection(Plan::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المميزات بنجاح','plans return successfully');
    }


  public function templates()
    {
        $success['templates']=TemplateResource::collection(Template::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع القوالب بنجاح','templates return successfully');
    }


  public function units($id)
    {
        $success['units']=UnitResource::collection(Unit::where('course_id',$id)->where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع القوالب بنجاح','templates return successfully');
    }

     public function post_categories()
    {
        $success['categories']=PostCategoryResource::collection(Postcategory::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع تصنيفات المقالات بنجاح','Post Categories return successfully');
    }


     public function page_categories()
    {
        $success['categories']=Page_categoryResource::collection(Page_category::where('is_deleted',0)->where('id','!=',2)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع تصنيفات الصفحات بنجاح','Page Categories return successfully');
    }
 public function roles()
    {
        $success['roles']=RoleResource::collection(Role::where('type','admin')->whereNot('id', 1)->orderByDesc('created_at')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الأدوار بنجاح','Roles return successfully');
    }
    public function subcategories(Request $request)
    {
      $input = $request->all();
        $validator =  Validator::make($input ,[
            'category_id'=>['required','array']

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $category = Category::whereIn('parent_id', $request->category_id)->where('store_id', null)->where('is_deleted', 0)->where('status', 'active')->get();

        $success['categories'] = CategoryResource::collection($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاقسام الفرعية بنجاح', 'sub_Category showed successfully');
    }

}
