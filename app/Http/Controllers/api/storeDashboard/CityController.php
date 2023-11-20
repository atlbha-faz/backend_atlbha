<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\City;
use App\Imports\SaeeImport;
use App\Imports\SmsaImport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\CityResource;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class CityController extends BaseController
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
        $success['cities']=CityResource::collection(City::where('is_deleted',0)->orderByDesc('created_at')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المدن بنجاح','cities return successfully');

    }

    public function importcities(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        try {

            // Excel::import(new SaeeImport, $request->file);
            Excel::import(new SaeeImport,request()->file('file'));

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إضافة المنتجات بنجاح', 'products Added successfully');
        } catch (ValidationException $e) {
            // Handle other import error
            // return "eroee";
            $failures = $e->failures();

            // Handle validation failures
            return $failures;
        }
    }
     public function importsmsacities(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        try {

            // Excel::import(new SaeeImport, $request->file);
            Excel::import(new SmsaImport,request()->file('file'));

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم إضافة المنتجات بنجاح', 'products Added successfully');
        } catch (ValidationException $e) {
            // Handle other import error
            // return "eroee";
            $failures = $e->failures();

            // Handle validation failures
            return $failures;
        }
    }


}
