<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\atlobhaContactResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\website_socialmediaResource;
use App\Models\AtlobhaContact;
use App\Models\Category;
use App\Models\City;
use App\Models\Comment;
use App\Models\Homepage;
use App\Models\Page;
use App\Models\Page_page_category;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Store;
use App\Models\website_socialmedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexEtlobhaController extends BaseController
{
    public function index()
    {
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

        $success['store_activities'] = CategoryResource::collection(Category::with(['subcategory' => function ($query) {
            $query->select('id');}])->where('is_deleted', 0)->where('parent_id', null)->where('store_id', null)->where('status', 'active')->orderByDesc('created_at')->get());
        $success['cities'] = CityResource::collection(City::where('is_deleted', 0)->where('status', 'active')->get());

        $success['section1'] = Section::where('id', 1)->pluck('name')->first();
        if (!is_null(Section::where('id', 1)->where('is_deleted', 0)->where('status', 'active')->first())) {
            $success['products'] = ProductResource::collection(Product::with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name');
            }, 'category' => function ($query) {
                $query->select('id', 'name');}])->where('is_deleted', 0)->where('admin_special', 'special')->select('id', 'name', 'status', 'cover', 'special', 'admin_special', 'store_id', 'created_at', 'category_id', 'subcategory_id', 'selling_price', 'stock')->get());
        } else {
            $success['products'] = array();
        }
        $success['section2'] = Section::where('id', 2)->pluck('name')->first();
        if (!is_null(Section::where('id', 2)->where('is_deleted', 0)->where('status', 'active')->first())) {
            $success['stores'] = StoreResource::collection(Store::with(['user' => function ($query) {
                $query->select('id');
            }, 'city' => function ($query) {
                $query->select('id', 'name');
            }, 'country' => function ($query) {
                $query->select('id', 'name');
            }])->where('is_deleted', 0)->where('special', 'special')->get());
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
        $success['footer'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', null)->select('id', 'title', 'status', 'created_at')->where('status', 'active')->whereIn('id', $pages)->get());
        $success['website_socialmedia'] = website_socialmediaResource::collection(website_socialmedia::where('is_deleted', 0)->where('status', 'active')->get());
        $success['registration_marketer'] = Setting::orderBy('id', 'desc')->pluck('registration_marketer')->first();

        $success['status'] = 200;

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
}
//
