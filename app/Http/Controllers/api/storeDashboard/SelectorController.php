<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\importsResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\Page_categoryResource;
use App\Http\Resources\PaymenttypeResource;
use App\Http\Resources\PlanResource;
use App\Http\Resources\PostCategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ShippingStoreResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\TemplateResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Package;
use App\Models\Page_category;
use App\Models\Paymenttype;
use App\Models\Plan;
use App\Models\Postcategory;
use App\Models\Product;
use App\Models\Service;
use App\Models\Shipping;
use App\Models\Shippingtype;
use App\Models\Store;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SelectorController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function products()
    {
        $success['products'] = ProductResource::collection(Product::where('is_deleted', 0)->where('status', 'active')->where('store_id', auth()->user()->store_id)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'Products return successfully');
    }

    public function payment_types()
    {
        $success['payment_types'] = PaymenttypeResource::collection(Paymenttype::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع طرق الدفع بنجاح', 'Payment Types return successfully');
    }

    public function services()
    {
        $success['services'] = ServiceResource::collection(Service::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الخدمات بنجاح', 'Services return successfully');
    }

    public function auth_user()
    {
        $success['auth_user'] = new StoreResource(Store::find(auth()->user()->store_id));
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المستخدم بنجاح', 'Auth User return successfully');
    }

    public function cities()
    {
        $success['cities'] = CityResource::collection(City::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المدن بنجاح', 'cities return successfully');
    }

    public function countries()
    {
        $success['countries'] = CountryResource::collection(Country::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الدول بنجاح', 'countries return successfully');
    }

    public function activities()
    {
        // $success['activities'] = ActivityResource::collection(Activity::where('is_deleted', 0)->where('status', 'active')->get());
        $success['activities'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('parent_id', null)->where('store_id', null)->orderByDesc('created_at')->get());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الأنشطة بنجاح', 'activities return successfully');
    }

    public function mainCategories()
    {

        $atlbhaCategory = Category::
            where('is_deleted', 0)
            ->where('parent_id', null)
            ->Where('store_id', null)
            ->where('status', 'active')->orderByDesc('created_at')->get();
        $storeCategory = Category::
            where('is_deleted', 0)
            ->where('parent_id', null)
            ->Where('store_id', auth()->user()->store_id)
            ->where('status', 'active')->orderByDesc('created_at')->get();
        $success['categories'] = CategoryResource::collection(  $storeCategory->merge( $atlbhaCategory) );
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');

    }
    // public function productCategories()
    // {

    //     $success['categories'] = CategoryResource::collection(Category::
    //             where('is_deleted', 0)
    //             ->where('parent_id', null)
    //             ->where(function ($query) {
    //                 $query->where('store_id', auth()->user()->store_id)
    //                     ->OrWhere('store_id', null);
    //             })
    //             ->where('status', 'active')->get());

    //     $success['status'] = 200;
    //     return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');

    // }
    public function children($parnet)
    {
        $category = Category::where('parent_id', $parnet)->where('is_deleted', 0)->where('status', 'active')->get();

        $success['categories'] = CategoryResource::collection($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاقسام الفرعية بنجاح', 'sub_Category showed successfully');
    }
// admin category
    public function mainCategories_etlobha()
    {
        $success['categories'] = CategoryResource::collection(Category::
                where('is_deleted', 0)
                ->where('parent_id', null)
                ->where('store_id', null)
                ->where('status', 'active')->get());
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');

    }
// admin category

    public function etlobahCategory(Request $request)
    {
        if ($request->has('page')) {
            $etlobahCategory = CategoryResource::collection(Category::
                    where('is_deleted', 0)

                    ->where('parent_id', null
                    )->where('store_id', null)->orderBy('created_at', 'DESC')->paginate(10));
            $pageNumber = request()->query('page', 1);
            $success['current_page'] = $etlobahCategory->currentPage();
            $success['page_count'] = $etlobahCategory->lastPage();
            $success['categories'] = $etlobahCategory;
        } else {
            $success['categories'] = CategoryResource::collection(Category::
                    where('is_deleted', 0)

                    ->where('parent_id', null
                    )->where('store_id', null)->get());
        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');
    }
    //  storeCategory with pagination
    public function storeCategory(Request $request)
    {
        if ($request->has('page')) {
            $storeCategory = CategoryResource::collection(Category::
                    where('is_deleted', 0)

                    ->where('parent_id', null
                    )->where('store_id', auth()->user()->store_id)->orderBy('created_at', 'DESC')->paginate(10));
            $pageNumber = request()->query('page', 1);
            $success['current_page'] = $storeCategory->currentPage();
            $success['page_count'] = $storeCategory->lastPage();
            $success['categories'] = $storeCategory;
        } else {
            $success['categories'] = CategoryResource::collection(Category::
                    where('is_deleted', 0)

                    ->where('parent_id', null
                    )->where('store_id', auth()->user()->store_id)->get());
        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع التصنيفات بنجاح', 'categories return successfully');
    }
    public function roles()
    {
        $success['roles'] = DB::table('roles')->where('type', 'store')->whereNot('name', 'المالك')->where('store_id', auth()->user()->store_id)->get();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الأدوار بنجاح', 'roles return successfully');
    }

    public function packages()
    {
        $success['packages'] = PackageResource::collection(Package::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الباقات بنجاح', 'packages return successfully');
    }

    public function plans()
    {
        $success['plans'] = PlanResource::collection(Plan::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المميزات بنجاح', 'plans return successfully');
    }

    public function templates()
    {
        $success['templates'] = TemplateResource::collection(Template::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع القوالب بنجاح', 'templates return successfully');
    }

    public function pagesCategory()
    {
        $success['pagesCategory'] = Page_categoryResource::collection(Page_category::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع تصنيف الصفحات بنجاح', 'Page_category return successfully');
    }

    public function serrvices()
    {
        $success['serrvices'] = ServiceResource::collection(Service::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الخدمات بنجاح', 'serrvices return successfully');
    }

    public function post_categories()
    {
        $success['categories'] = PostCategoryResource::collection(Postcategory::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع تصنيفات المقالات بنجاح', 'Post Categories return successfully');
    }

    public function storeImportproduct()
    {

        $products = ProductResource::collection(Product::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());

        $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
        $imports = importsResource::collection($import);

        $success['products'] = $products->merge($imports);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }
    public function subcategories(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'category_id' => ['required', 'array'],
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $category = Category::whereIn('parent_id', $request->category_id)->where('is_deleted', 0)->where('status', 'active')->get();

        $success['categories'] = CategoryResource::collection($category);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاقسام الفرعية بنجاح', 'sub_Category showed successfully');
    }
    public function show()
    {
        $shipping = Shipping::where('store_id', auth()->user()->store_id)->latest('updated_at')->first();

        $success['shippingAddress'] = $shipping !== null ? new ShippingStoreResource($shipping) : null;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع عنوان المستودع بنجاح', 'address return successfully');
    }
    public function shippingcities($id)
    {
        $shippingCompany = Shippingtype::query()->find($id);
        $success['cities'] = ShippingCitiesResource::collection($shippingCompany->shippingcities()->where('status', 'active')->get());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع  المدن بنجاح', 'city return successfully');

    }

}
