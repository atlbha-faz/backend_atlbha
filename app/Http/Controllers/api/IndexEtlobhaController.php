<?php

namespace App\Http\Controllers\api;

use App\Models\City;
use App\Models\Page;
use App\Models\Store;
use App\Models\Comment;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Models\AtlobhaContact;
use App\Models\CommonQuestion;
use App\Models\categories_stores;
use App\Models\Page_page_category;
use App\Models\website_socialmedia;
use App\Http\Resources\CityResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\atlobhaContactResource;
use App\Http\Resources\CommonQuestionResource;
use App\Http\Resources\website_socialmediaResource;
use App\Http\Resources\AtlbhaIndexProductResource;
use App\Http\Resources\AtlbhaIndexSearchProductResource;
use App\Http\Resources\AtlbhaIndexSearchStoreResource;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexEtlobhaController extends BaseController
{
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        // visit count
        $homepage = Homepage::where('is_deleted', 0)->where('store_id', null)->first();
        views($homepage)->record();
        $success['countVisit'] = views($homepage)->count();
        //
        $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', null)->pluck('logo')->first();
        $success['logo_footer'] = Homepage::where('is_deleted', 0)->where('store_id', null)->pluck('logo_footer')->first();
        $success['slider1'] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('sliderstatus1', 'active')->pluck('slider1')->first();
        $success['slider2'] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('sliderstatus2', 'active')->pluck('slider2')->first();
        $success['slider3'] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('sliderstatus3', 'active')->pluck('slider3')->first();

        $success['banar1'] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('banarstatus1', 'active')->pluck('banar1')->first();
        $success['banar2'] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('banarstatus2', 'active')->pluck('banar2')->first();
        $success['banar3'] = Homepage::where('is_deleted', 0)->where('store_id', null)->where('banarstatus3', 'active')->pluck('banar3')->first();
        $categoriesStore = categories_stores::whereNot('store_id', null)->pluck('category_id')->toArray();
        $success['store_activities'] = CategoryResource::collection(Category::with(['subcategory' => function ($query) {
            $query->select('id');}])->where('is_deleted', 0)->where('parent_id', null)->where('store_id', null)->whereIn('id', $categoriesStore)->where('status', 'active')->orderByDesc('created_at')->get());

        $citiesStore = Store::where('is_deleted', 0)->where('verification_status', 'accept')->pluck('city_id')->toArray();
        $success['cities'] = CityResource::collection(City::where('is_deleted', 0)->where('status', 'active')->whereIn('id', $citiesStore)->get());

        $success['section1'] = Section::where('id', 1)->pluck('name')->first();
        if (!is_null(Section::where('id', 1)->where('is_deleted', 0)->where('status', 'active')->first())) {
            $success['products'] = AtlbhaIndexProductResource::collection(Product::with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name');
            }, 'category' => function ($query) {
                $query->select('id', 'name');},'importproduct'])->where('is_deleted', 0)->where('admin_special', 'special')->select('id', 'name', 'status', 'cover', 'special', 'admin_special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'stock')->get());
        } else {
            $success['products'] = array();
        }
        $success['section2'] = Section::where('id', 2)->pluck('name')->first();
        if (!is_null(Section::where('id', 2)->where('is_deleted', 0)->where('status', 'active')->first())) {

$stores=Store::with(['user' => function ($query) {
                $query->select('id');
            }, 'city' => function ($query) {
                $query->select('id', 'name');
            }, 'country' => function ($query) {
                $query->select('id', 'name');
            }])->where('is_deleted', 0)->where('special', 'special');
            $stores = $stores->paginate($count);
            $success['stores'] = StoreResource::collection($stores);
            $success['stores_total_result'] = $stores->total();
            $success['stores_page_count'] = $stores->lastPage();
            $success['stores_current_page'] = $stores->currentPage();
        } else {
            $success['stores'] = array();
        }
        // $success['packages'] = PackageResource::collection(Package::where('is_deleted', 0)->get());

        $success['comment'] = CommentResource::collection(Comment::with(['user' => function ($query) {
            $query->with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name', 'logo');
            }]);
        }])->where('is_deleted', 0)->where('comment_for', 'store')->where('status', 'active')->where('store_id', null)->where('product_id', null)->latest()->take(10)->get());
        $success['partners'] = PartnerResource::collection(Partner::where('is_deleted', 0)->get());

        $pages = Page_page_category::where('page_category_id', 3)->pluck('page_id')->toArray();
        // $startpages = Page_page_category::where('page_category_id', 2)->pluck('page_id')->toArray();
        // $success['start'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->select('id', 'title', 'status', 'created_at')->where('status', 'active')->whereIn('id',$startpages)->get());
        $success['footer'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->select('id', 'title', 'status','page_content','page_desc', 'created_at')->where('status', 'active')->whereIn('id', $pages)->get());
        $success['website_socialmedia'] = website_socialmediaResource::collection(website_socialmedia::where('is_deleted', 0)->where('status', 'active')->get());
        $success['registration_marketer'] = Setting::orderBy('id', 'desc')->pluck('registration_marketer')->first();

        $success['status'] = 200;
        $success['atlbha_products'] = ProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');}])->where('store_id', null)->where('for', 'etlobha')->whereNot('stock', 0)->orderByDesc('created_at')->select('id', 'name', 'cover', 'selling_price', 'purchasing_price', 'stock', 'less_qty', 'created_at','short_description', 'category_id', 'subcategory_id') ->limit(20)
            ->get());
        return $this->sendResponse($success, 'تم ارجاع الرئيسية بنجاح', 'etlobha index return successfully');
    }

    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $atlobhaContact = AtlobhaContact::create([
            'name' => $request->name,
            'email' => $request->email,
            'title' => $request->title,
            'content' => $request->content,
        ]);
        $success['atlobhaContact'] = new atlobhaContactResource($atlobhaContact);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الرسالة  بنجاح', 'message Added successfully');
    }
    public function commonquestion()
    {

        $success['commonQuestions']=CommonQuestionResource::collection(CommonQuestion::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الاسئلة بنجاح','Questions return successfully');
    }
      public function searchIndex(Request $request){
        
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $query1= AtlbhaIndexSearchProductResource::collection(Product::with(['store' => function ($query) {
            $query->select('id', 'domain', 'store_name');
        }, 'category' => function ($query) {
            $query->select('id', 'name');
        }])->where('is_deleted', 0)->where('name', 'like', "%$query%")->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'purchasing_price', 'discount_price', 'stock', 'description', 'short_description')->get());

        $query2 =AtlbhaIndexSearchStoreResource::collection( Store::with(['categories' => function ($query) {
            $query->select('name', 'icon');
        }, 'city' => function ($query) {
            $query->select('id','name');
        }, 'country' => function ($query) {
            $query->select('id');
        }, 'user' => function ($query) {
            $query->select('id');
        }])->where('is_deleted', 0)->where('verification_status', '!=', 'pending')->where('store_name', 'like', "%$query%")->orderByDesc('created_at')->select('id', 'store_name', 'domain','phonenumber', 'status', 'periodtype', 'logo', 'icon', 'special','store_email','verification_status', 'city_id','verification_date', 'created_at')->get());
        $results =$query1->merge($query2);

        $success['results'] = $results;

        return $this->sendResponse($success, 'تم ارجاع نتائج البحث بنجاح', 'search Information returned successfully');
   
    }
    
   public function storesFilter(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ?                          $request->input('number') : 10;

        $data = Store::with(['user' => function ($query) {
            $query->select('id');
        },
        ])->where('is_deleted', 0)->where('special', 'special');

        if ($request->has('name')) {
            $name = $request->input('name');
            $data->where('store_name', 'like', "%$name%");
        }
        if ($request->has('city_id')) {
            $data->where('city_id', $request->input('city_id'));
        }
        if ($request->has('category_id')) {
            $data->whereHas('categories', function ($q) use($request) {
                $q->where('category_id', $request->input('category_id'));
            });
        }
        $data = $data->paginate($count);
        $success['total_result'] = $data->total();
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
        $success['stores'] = StoreResource::collection($data);
        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'etlobha stores return successfully');

    }

   
}
//
