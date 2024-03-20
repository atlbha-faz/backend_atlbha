<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\City;
use App\Imports\JTImport;
use App\Imports\SmsaImport;
use App\Imports\ImileImport;
use App\Models\ShippingCity;
use Illuminate\Http\Request;
use App\Imports\AramexImport;
use App\Http\Resources\CityResource;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\shippingcities_shippingtypes;
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
        $success['cities'] = CityResource::collection(City::where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المدن بنجاح', 'cities return successfully');

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
            Excel::import(new AramexImport, request()->file('file'));

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
