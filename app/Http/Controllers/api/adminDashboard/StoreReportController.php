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
use App\Models\Websiteorder;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;
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
        $success['average_of_stores']=((Store::where('is_deleted',0)->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Store::where('is_deleted',0)->count())*100)."%";
         $success['active_of_stores']=Store::where('is_deleted',0)->where('status','active')->count();
         $success['not_active_of_stores']=Store::where('is_deleted',0)->where('status','not_active')->count();
         $success['latest_stores']=Store::orderBy('id','DESC')->take(5)->get();
         $success['last_24_hours_of_stores']=Store::where('is_deleted',0)->where('created_at', '>=', Carbon::now()->subDay())->count();
         $success['last_24_hours_of_pending_orders']=Websiteorder::where('is_deleted',0)->where('created_at', '>=', Carbon::now()->subDay())->where('type','store')->where('status','pending')->count();
         $success['last_24_hours_of_complete_orders']=Websiteorder::where('is_deleted',0)->where('created_at', '>=', Carbon::now()->subDay())->where('type','store')->where('status','accept')->count();
     $success['last_month_of_stores']=Store::where('is_deleted',0)->where('created_at', '>=', Carbon::now()->month())->count();
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
    public function home(){
        $success['count_of_stores']=Store::where('is_deleted',0)->count();
        $success['average_of_stores']=((Store::where('is_deleted',0)->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Store::where('is_deleted',0)->count())*100)."%";

         $success['count_of_marketers']=Marketer::all()->count();
         $success['average_of_marketers']=((Marketer::whereHas('user', function($q){
            $q->where('is_deleted', 0);})->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Marketer::whereHas('user', function($q){
            $q->where('is_deleted', 0);
        })->count())*100)."%";
         $success['count_of_services']=Service::where('is_deleted',0)->count();
         $success['average_of_services']=((Service::where('is_deleted',0)->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Service::where('is_deleted',0)->count())*100)."%";
       
        $count=6;
        $array_count_store = array(); 
        while($count >= 0) {
            $array_count_store[$count]["count of store before ".$count." month"]= Store::where('is_deleted',0)->where('status','active')->whereMonth('created_at','=',Carbon::now()->subMonths($count)->month)->count();
            $count--;
          }
          
         
          $success['array_store']= $array_count_store; 
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
            if($store->city->id == $city->id ){
           if($store->periodtype=="year")
           $array_city_store[$store->city->name]= $array_city_store[$store->city->name]+ $package->yearly_price;
           else
           $array_city_store[$store->city->name]= $array_city_store[$store->city->name]+ $package->monthly_price;
         }
        }
        }
        }

        $success['Subscriptions-city']=  $array_city_store;
           $success['Subscriptions']=  $sum;
        $success['status']= 200;
        return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');
        
    }

   
    
}
