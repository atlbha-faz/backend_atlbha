<?php

namespace App\Http\Controllers\api\adminDashboard;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Order;
use App\Models\Store;
use App\Models\Package;
use App\Models\Product;
use App\Models\Service;
use App\Models\Marketer;
use Carbon\CarbonPeriod;
use App\Models\Websiteorder;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class StoreReportController extends  BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $success['count_of_stores']=Store::where('is_deleted',0)->count();
        if($success['count_of_stores']>0){
        $success['average_of_stores']=((Store::where('is_deleted',0)->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Store::where('is_deleted',0)->count())*100)."%";
        }
        else{
          $success['average_of_stores']=0;
        }
         $success['active_of_stores']=Store::where('is_deleted',0)->where('status','active')->count();
         $success['not_active_of_stores']=Store::where('is_deleted',0)->where('status','not_active')->count();
         $success['latest_stores']=Store::where('is_deleted',0)->where('status','active')->orderBy('created_at','DESC')->take(5)->get();
         $success['last_24_hours_of_stores']=Store::where('is_deleted',0)->where('status','active')->where('created_at', '>=', Carbon::now()->subDay())->count();
         $success['last_24_hours_of_pending_orders']=Websiteorder::where('is_deleted',0)->where('created_at', '>=', Carbon::now()->subDay())->where('type','store')->where('status','pending')->count();
         $success['last_24_hours_of_complete_orders']=Websiteorder::where('is_deleted',0)->where('created_at', '>=', Carbon::now()->subDay())->where('type','store')->where('status','accept')->count();
     $success['last_month_of_stores']=Store::where('is_deleted',0)->where('status','active')->where('created_at', '>=', Carbon::now()->month())->count();
           $success['last_month_of_complete_orders']=Websiteorder::where('is_deleted',0)->where('created_at', '>=', Carbon::now()->month())->where('type','store')->where('status','accept')->count();


        $array_store = array();

        for($i = 1; $i <= 12; $i++){
            $array_store[$i]["active"]= Store::where('is_deleted',0)->where('status','active')->whereYear('created_at', $request->year)->whereMonth('created_at', $i)->count();
            $array_store[$i]["not_active"]= Store::where('is_deleted',0)->where('status','not_active')->whereYear('created_at', $request->year)->whereMonth('created_at', $i)->count();
        }
        $success['array_store']= $array_store;

        $success['status']= 200;
         return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');
    }
    public function home(Request $request){
        $input = $request->all();

        $validator =  Validator::make($input ,[
            'startDate1'=>'date',
            'endDate1'=>'date',
            'startDate2'=>'date',
            'endDate2'=>'date',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $startDate1=$request->startDate1;
        $endDate1=$request->endDate1;
        $startDate2=$request->startDate2;
        $endDate2=$request->endDate2;

        $success['count_of_stores']=Store::where('is_deleted',0)->where('status','active')->count();
        if(   $success['count_of_stores']>0){
        $success['average_of_stores']=((Store::where('is_deleted',0)->where('status','active')->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Store::where('is_deleted',0)->where('status','active')->count())*100)."%";
        }
        else{
          $success['average_of_stores']=0;
        }
         $success['count_of_marketers']=Marketer::whereHas('user', function($q){
          $q->where('is_deleted', 0);
                   })->count();
                   if( $success['count_of_marketers']>0){
         $success['average_of_marketers']=((Marketer::whereHas('user', function($q){
            $q->where('is_deleted', 0);})->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Marketer::whereHas('user', function($q){
            $q->where('is_deleted', 0);
        })->count())*100)."%";
      }
      else{
        $success['average_of_marketers']=0;
      }
         $success['count_of_services']=Service::where('is_deleted',0)->count();
         if( $success['count_of_services']>0){
            $success['average_of_services']=((Service::where('is_deleted',0)->whereYear('created_at', Carbon::now()->year)
           ->whereMonth('created_at', Carbon::now()->month)->count())/(Service::where('is_deleted',0)->count())*100)."%";
         }
         else{
          $success['average_of_services']=0;
         }
// احمالي المتاجر خلال 6 شهور
          if(is_null($request->startDate1) || is_null($request->endDate1))
          {
             $sum=0;
            $p=Package::where('is_deleted',0)->count();
            for($i = 1; $i <= $p; $i++){
           $package = Package::query()->find($i);
           $stores=$package->stores->where('created_at', '>=', Carbon::now()->subMonths(6)->month);
           foreach($stores as $store){
             if($store->periodtype=="year")
             $sum=$sum+ $package->yearly_price;
             else
             $sum=$sum+  $package->monthly_price;
           }
          }
          $success['Subscriptions_withPeriod']=  $sum;

            $count=6;
            $array_count_store = array();
            while($count >= 0) {
                $array_count_store[mb_substr(Carbon::now()->subMonths($count)->format('F'),0,3)]= Store::where('is_deleted',0)->where('status','active')->whereMonth('created_at','=',Carbon::now()->subMonths($count)->month)->count();
                $count--;
            }


            $success['array_store']= $array_count_store;
        }
        // احمالي المتاجر خلال الفترة المحددة
        else{
             $sum=0;
            $p=Package::where('is_deleted',0)->count();
            for($i = 1; $i <= $p; $i++){
           $package = Package::query()->find($i);
           $stores=$package->stores->whereBetween('created_at', [$startDate1.' 00:00:00',$endDate1.' 23:59:59']);
           foreach($stores as $store){
             if($store->periodtype=="year")
             $sum=$sum+ $package->yearly_price;
             else
             $sum=$sum+  $package->monthly_price;
           }
          }
          $success['Subscriptions_withPeriod']=  $sum;

        $period = CarbonPeriod::create($startDate1, $endDate1)->month();
                $months = collect($period)->map(function (Carbon $date) {
              return  $date->monthName;
                })->toArray();
                $array_count_store = array();
        foreach($months as $month) {
            $array_count_store[mb_substr($month,0,3)]= Store::where('is_deleted',0)->where('status','active')->whereBetween('created_at', [$startDate1.' 00:00:00',$endDate1.' 23:59:59'])->whereMonth('created_at',$month)->count();

          }


          $success['array_store']= $array_count_store;
        }
            $sum=0;
            $p=Package::where('is_deleted',0)->count();
            for($i = 1; $i <= $p; $i++){
           $package = Package::query()->find($i);
           $stores=$package->stores->where('created_at','>=', Carbon::now()->subDays(30)->toDateTimeString());
           foreach($stores as $store){
             if($store->periodtype=="year")
             $sum=$sum+ $package->yearly_price;
             else
             $sum=$sum+  $package->monthly_price;
           }
          }
          $success['Subscriptions']=  $sum;
           //  ايرادات اطلبها خلال شهر
                $sum_service=0;
                $p=Service::where('is_deleted',0)->count();
                $websiteorders=Websiteorder::where('type','service')->where('status','accept')->get();
                foreach ($websiteorders as $websiteorder){
                foreach ($websiteorder->services as $service)
              {
               $sum_service=$sum_service+$service->price;
              }
              }
              $etlobha_income=Order::where('store_id',null)->where('order_status','completed')->where('created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString())->sum('total_price');

                $success['Etlobha_income']=$sum_service+$etlobha_income;

              // إجمالي الإيرادات
                 $success['all_income']=Order::where('order_status','completed')->where('created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString())->sum('total_price');

               if(is_null($request->startDate2) || is_null($request->endDate2)){
               //  اجمالي الطلبات خلال 6 شهور
                $success['Total_orders']=Order::where('store_id',null)->where('order_status','completed')->where('created_at', '>=', Carbon::now()->subMonths(6)->month)->count();
                //مخطط الطلبات خلال 6 شهور
                $count_month=6;
                $array_count_Etlobha = array();
                while($count_month >= 0) {
                    $array_count_Etlobha[mb_substr(Carbon::now()->subMonths($count_month)->format('F'),0, 3)]= Order::where('store_id',null)->where('order_status','completed')->whereMonth('created_at', Carbon::now()->subMonths($count_month)->month)->count();
                    $count_month--;
                  }

                  $success['count_orders_of_Etlobha']=   $array_count_Etlobha;
                }
                 else{
                    //  اجمالي الطلبات خلال 6 شهور
                $success['Total_orders']=Order::where('store_id',null)->where('order_status','completed')->whereBetween('created_at', [$startDate2.' 00:00:00',$endDate2.' 23:59:59'])->count();
                //مخطط الطلبات خلال 6 شهور

                $period = CarbonPeriod::create($startDate2, $endDate2)->month();
                $months = collect($period)->map(function (Carbon $date) {
              return  $date->monthName;
                })->toArray();
                // dd($months );
                $array_count_Etlobha = array();
                foreach($months as $month) {
                    $array_count_Etlobha[mb_substr($month,0, 3)]= Order::where('store_id',null)->where('order_status','completed')->whereBetween('created_at', [$startDate2.' 00:00:00',$endDate2.' 23:59:59'])->whereMonth('created_at',$month)->count();
                  }

                  $success['count_orders_of_Etlobha']=   $array_count_Etlobha;
                }

          $cities=City::where('is_deleted',0)->get();
          $array_city_store = array();
          foreach($cities as $city){
            $array_city_store[$city->name]=0;
          }

         

          $packageCount=Package::where('is_deleted',0)->count();
          for($i = 1; $i <= $packageCount; $i++){
         $package = Package::query()->find($i);
         $stores=$package->stores;
         foreach($stores as $store){
          foreach($cities as $city){
            if($store->city != null){
            if($store->city->id == $city->id ){
           if($store->periodtype=="year")
           $array_city_store[$store->city->name]= $array_city_store[$store->city->name]+ $package->yearly_price;
           else
           $array_city_store[$store->city->name]= $array_city_store[$store->city->name]+ $package->monthly_price;
         }
        }
        }
        }
        }
        arsort($array_city_store);
        $success['Subscriptions_city']=  array_slice($array_city_store, 0, 6, true);


         $success['more_product_visit']=ProductResource::collection(Product::where('is_deleted',0)->where('status','active')->latest()->take(5)->get());
        $success['more_store_visit']=StoreResource::collection(Store::where('is_deleted',0)->where('status','active')->latest()->take(5)->get());
        $success['status']= 200;
        return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');

    }



}
