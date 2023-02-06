<?php

namespace App\Http\Controllers\api\adminDashboard;

use Carbon\Carbon;
use App\Models\Store;
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
         $success['latest_stores']=Store::orderBy('id','desc')->take(5)->get();
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
         $success['count_of_services']=Service::where('is_deleted',0)->count();
        $success['status']= 200;
        return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');

    }

   
    
}
