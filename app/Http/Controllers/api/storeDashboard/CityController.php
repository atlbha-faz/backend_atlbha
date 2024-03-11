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
            Excel::import(new SmsaImport, request()->file('file'));

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
    public function importimilecities(Request $request)
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
            Excel::import(new ImileImport, request()->file('file'));

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
    public function importjtcities(Request $request)
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
            Excel::import(new JTImport, request()->file('file'));

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
    public function fiximport(Request $request)
    {
        $cities = ShippingCity::whereIn('region_id', [28, 29, 30, 31, 32])->get();

        foreach ($cities as $city) {
            $shipping_citest = shippingcities_shippingtypes::where('shipping_city_id', $city->id)->first();

            $shipping_citest->update([
                'shippingtype_id' => 3,
            ]);
        }
        return 200;
    }
    public function fixActive()
    {

            $cities = ShippingCity::whereIn('region_id', [14, 15, 16, 17, 18])->get();
            foreach ($cities as $city) {
                    $city->update(['status' => 'not_active']);
            }
            return 200;
    }
    public function fixCity()
    {
        $citiesName=[
            "Makkah City",
            "Al Awamiyah",
            "Al Badayea",
            "Tayma",
            "Taif",
            "Al Khurma",
            "Abha",
            "Uglat Asugour",
            "Yanbu",
            "Al Qurayyat",
            "Sharorah",
            "Thadiq",
            "Ayn Ibn Fuhayd",
            "Howtat Bani Tamim",
            "Turbah",
            "Najran",
            "Al-Kharj",
            "Al Qatif",
            "Khamis Mushait",
            "Al Husayy",
            "Arar",
            "Khulais",
            "Al Ajfar",
            "Sabya",
            "Mecca",
            "Al Jubail",
            "Qaryat Al Ulya",
            "Jazan",
            "Az Zulfi",
            "Tabuk",
            "Al Duwadimi",
            "Ahad Rafidah",
            "Hafar Al Batin",
            "Baish",
            "Ar Rass",
            "Ar Ruwaidah",
           "Al Khobar",
            "Buraydah",
            "Al Aqiq",
            "Alhuwaya",
            "Anak",
            "Medina",
            "Al Hofuf",
            "Ajman",
            "Khamis",
            "Samtah",
            "Al Aflag",
            "RiyadhA",
            "Makkah",
            "Madinah",
            "Riyadh",
            "Dammam",
            "Jeddah"];
            $cities = ShippingCity::whereIn('name_en', $citiesName)->whereIn('region_id', [14, 15, 16, 17, 18])->get();
            foreach ($cities as $city) {
                    $city->update(['status' => 'active']);
            }
            return 200;
    }

}
