<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexStoreController extends BaseController
{
    public function index($id){
        // visit count
        // $homepage=Homepage::where('is_deleted',0)->where('store_id',null)->first();
        //   views($homepage)->record();
        //    $success['countVisit']= views($homepage)->count();
            //
         $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo')->first();
         $success['logo_footer']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo_footer')->first();
         $success['slider1']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus1','active')->pluck('slider1')->first();
         $success['slider2']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus2','active')->pluck('slider2')->first();
         $success['slider3']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus3','active')->pluck('slider3')->first();

         $success['banar1']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus1','active')->pluck('banar1')->first();
         $success['banar2']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus2','active')->pluck('banar2')->first();
         $success['banar3']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus3','active')->pluck('banar3')->first();
// special products
  $success['specialProducts']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',$id)->where('special','special')->orderBy('created_at', 'desc')->take(4)->get());


///////////////////////////
$success['categoriesHaveSpecial']=Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->whereHas('products', function ($query) {
  $query->where('special', 'special');
})->get();
//
    // more sale
     $success['more_sales']=Order::where('store_id',$id)->where('order_status','completed')->orderBy('created_at', 'desc')->take(7)->get();
// resent arrivede

$oneWeekAgo = Carbon::now()->subWeek();

$success['resent_arrivede']=Product::where('is_deleted',0)
     ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->take(6)->get();
////////////////////////////////////////
$resent_arrivede_by_category=Category::where('is_deleted',0)->where('store_id',$id)->whereHas('products', function ($query) {
  $query->whereDate('created_at', '>=', Carbon::now()->subWeek());
})->get();

  foreach($resent_arrivede_by_category as $category){
 $success['resent_arrivede_by_category'][]=collect($category)->merge(Product::where('is_deleted',0)
     ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->where('category_id',$category->id)->get());
  }
 $success['status']= 200;
         return $this->sendResponse($success,'تم ارجاع الرئيسية للمتجر بنجاح','Store index return successfully');

    }
}
