<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Resources\CountryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class CountryController extends  BaseController
{

  public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries=Country::where('is_deleted',0)->orderByDesc('created_at')->paginate(10);
        $success['countries']=CountryResource::collection($countries);
        $success['page_count'] = $countries->lastPage();
        $success['current_page'] =  $countries->currentPage();
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الدول بنجاح','countries return successfully');

    }


}
