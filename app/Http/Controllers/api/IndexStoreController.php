<?php

namespace App\Http\Controllers\api;

use DB;
use Carbon\Carbon;
use App\Models\Page;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Store;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
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
        //  $success['logoFooter']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo_footer')->first();
        $sliders = Array();
        $sliders[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus1','active')->pluck('slider1')->first();
        $sliders[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus2','active')->pluck('slider2')->first();
        $sliders[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus3','active')->pluck('slider3')->first();
        $success['sliders']=$sliders;
         $banars = Array();
        $banars[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus1','active')->pluck('banar1')->first();
        $banars[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus2','active')->pluck('banar2')->first();
        $banars[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus3','active')->pluck('banar3')->first();
        $success['banars']=$banars;
        //  $success['blogs']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->get());

// special products
  $success['specialProducts']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',$id)->where('special','special')->orderBy('created_at', 'desc')->get());


///////////////////////////
$success['categoriesHaveSpecial']=Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->whereHas('products', function ($query) {
  $query->where('is_deleted',0)->where('special', 'special');
})->get();
//
    // more sale

  $arr=array();
    $orders=DB::table('order_items')->where('order_status','completed')->join('products', 'order_items.product_id', '=', 'products.id')->where('products.store_id',$id)
              ->select('products.id',DB::raw('sum(order_items.quantity) as count'))
                 ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();
        
    
    foreach($orders as  $order)
    {
     $arr[]=Product::find($order->id);
        
}
$success['moreSales']= ProductResource::collection($arr);
// resent arrivede

$oneWeekAgo = Carbon::now()->subWeek();

$success['resentArrivede']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->get());
////////////////////////////////////////
$resent_arrivede_by_category=Category::where('is_deleted',0)->where('store_id',$id)->whereHas('products', function ($query) use($id)  {
  $query->where('is_deleted',0)->where('store_id',$id)->whereDate('created_at', '>=', Carbon::now()->subWeek());
})->get();

  foreach($resent_arrivede_by_category as $category){

 $success['resentArrivedeByCategory'][][$category->name]=ProductResource::collection(Product::where('is_deleted',0)
 ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->where('category_id',$category->id)->get());
  }

         $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id',null)->get());
        $success['lastPosts']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->orderBy('created_at', 'desc')->take(6)->get());
         $success['category']=CategoryResource::collection(Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get());
         $success['productsOffers']=Offer::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get();
        $success['productsRatings']=Comment::where('is_deleted',0)->where('store_id',$id)->orderBy('rateing', 'DESC')->with('product')->has('product')->take(3)->get();
        $productsCategories=Product::where('store_id',$id)->whereHas('category', function ($query) {
  $query->where('is_deleted',0);
})->groupBy('category_id')->selectRaw('count(*) as total, category_id')->orderBy('total','DESC')->take(6)->get();
       foreach( $productsCategories as  $productsCategory){
        $success['PopularCategories'][]=new CategoryResource(Category::where('is_deleted',0)->where('store_id',$id)->where('id', $productsCategory->category_id)->first());
       }
         $success['storeName']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_name')->first();
         $success['storeEmail ']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_email')->first();
         $success['phonenumber']=Store::where('is_deleted',0)->where('id',$id)->pluck('phonenumber')->first();
         $success['description']=Store::where('is_deleted',0)->where('id',$id)->pluck('description')->first();
         $success['snapchat']=Store::where('is_deleted',0)->where('id',$id)->pluck('snapchat')->first();
         $success['facebook']=Store::where('is_deleted',0)->where('id',$id)->pluck('facebook')->first();
         $success['twiter']=Store::where('is_deleted',0)->where('id',$id)->pluck('twiter')->first();
         $success['youtube']=Store::where('is_deleted',0)->where('id',$id)->pluck('youtube')->first();
         $success['instegram']=Store::where('is_deleted',0)->where('id',$id)->pluck('instegram')->first();
         $store=Store::where('is_deleted',0)->where('id',$id)->first();
         $success['paymentMethod']=$store->paymenttypes->where('status','active');
         $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الرئيسية للمتجر بنجاح','Store index return successfully');

    }

    public function productPage($id){
       $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo')->first();
         $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id',null)->get());
        $product=Product::where('is_deleted',0)->where('id',$id)->first();
        $success['product']=NEW ProductResource(Product::where('is_deleted',0)->where('id',$id)->first());
        $success['relatedProduct']=ProductResource::collection(Product::where('is_deleted',0)
                ->where('store_id',$product->store_id)->where('category_id',$product->category_id)->whereNotIn('id', [$id])->get());

        $success['commentOfProducts']=CommentResource::collection(Comment::where('is_deleted',0)->where('comment_for','product')->where('store_id', $product->store_id)->where('product_id',$product->id)->get());
        $success['storeName']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_name')->first();
         $success['storeEmail ']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_email')->first();
         $success['phonenumber']=Store::where('is_deleted',0)->where('id',$id)->pluck('phonenumber')->first();
         $success['description']=Store::where('is_deleted',0)->where('id',$id)->pluck('description')->first();

         $success['snapchat']=Store::where('is_deleted',0)->where('id',$id)->pluck('snapchat')->first();
         $success['facebook']=Store::where('is_deleted',0)->where('id',$id)->pluck('facebook')->first();
         $success['twiter']=Store::where('is_deleted',0)->where('id',$id)->pluck('twiter')->first();
         $success['youtube']=Store::where('is_deleted',0)->where('id',$id)->pluck('youtube')->first();
         $success['instegram']=Store::where('is_deleted',0)->where('id',$id)->pluck('instegram')->first();
         $store=Store::where('is_deleted',0)->where('id',$id)->first();
         $success['paymentMethod']=$store->paymenttypes->where('status','active');
         $success['status']= 200;

        return $this->sendResponse($success,'تم ارجاع صفحة المنتج للمتجر بنجاح',' Product page return successfully');

    }
    public function addComment(Request $request,$id)
    {

        $product= Product::query()->find($id);
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'comment_text'=>'required|string|max:255',
            'rateing'=>'required|numeric|lt:5',

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $comment = Comment::create([
            'comment_text' => $request->comment_text,
            'rateing' => $request->rateing,
            'comment_for' =>'product',
            'product_id' => $id,
            'store_id' => $product->store_id,
            'user_id' => auth()->user()->id,

          ]);


         $success['comments']=New CommentResource($comment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة تعليق بنجاح','comment Added successfully');

    }
    public function storPage(Request $request,$id){
      $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$request->id)->pluck('logo')->first();
      $success['category']=CategoryResource::collection(Category::where('is_deleted',0)->where('store_id',$request->id)->with('products')->has('products')->get());
      $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$request->id)->where('postcategory_id',null)->get());
      $success['page']=PageResource::collection(Page::where('is_deleted',0)->where('id',$id)->where('store_id',$request->id)->where('postcategory_id',null)->get());
      $success['storeName']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('store_name')->first();
      $success['storeEmail ']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('store_email')->first();
      $success['phonenumber']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('phonenumber')->first();
      $success['description']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('description')->first();

      $success['snapchat']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('snapchat')->first();
      $success['facebook']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('facebook')->first();
      $success['twiter']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('twiter')->first();
      $success['youtube']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('youtube')->first();
      $success['instegram']=Store::where('is_deleted',0)->where('id',$request->id)->pluck('instegram')->first();
      $success['status']= 200;
      return $this->sendResponse($success,'تم  الصفحة للمتجر بنجاح','Store page return successfully');
    }

    // المدونه

    }


