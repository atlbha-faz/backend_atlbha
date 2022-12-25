<?php

namespace App\Http\Controllers\api\adminDashboard;

use Carbon\Carbon;
use App\Models\Store;
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
        $success['count of stores']=Store::where('is_deleted',0)->count();
        $success['average of stores']=((Store::whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)->count())/(Store::where('is_deleted',0)->count())*100)."%";
         $success['active of stores']=Store::where('status','active')->count();
         $success['not_active of stores']=Store::where('status','not_active')->count();
         $success['latest stores']=Store::latest()->take(5)->get();
         $success['last 24 hours of stores']=Store::where('created_at', '>=', Carbon::now()->subDay())->count();
         $success['last 24 hours of pending orders']=Websiteorder::where('created_at', '>=', Carbon::now()->subDay())->where('type','store')->where('status','pending')->count();
         $success['last 24 hours of complete orders']=Websiteorder::where('created_at', '>=', Carbon::now()->subDay())->where('type','store')->where('status','accept')->count();

        
        $array_store = array(); 

        for($i = 1; $i <= 12; $i++){ 
            $array_store[$i]["active"]= Store::where('status','active')->whereYear('created_at', $request->year)->whereMonth('created_at', $i)->count();
            $array_store[$i]["not_active"]= Store::where('status','not_active')->whereYear('created_at', $request->year)->whereMonth('created_at', $i)->count();
        }
        $success['array_store']= $array_store;
        
        $success['status']= 200;
         return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');
    }
   
    
}