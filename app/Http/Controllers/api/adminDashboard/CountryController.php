<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class CountryController extends BaseController
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
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data = Country::where('is_deleted', 0)->orderByDesc('created_at');
        $data = $data->paginate($count);
        $success['countries'] = CountryResource::collection($data);
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الدول بنجاح', 'countries return successfully');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
       
        $country = Country::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'code' => $request->code,
        ]);

        // return new CountryResource($country);
        $success['countries'] = new CountryResource($country);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة دولة بنجاح', 'country Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        $country = Country::query()->find($country->id);
        if (is_null($country) || $country->is_deleted != 0) {
            return $this->sendError("الدولة غير موجودة", "country is't exists");
        }
        $success['$countries'] = new CountryResource($country);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'country showed successfully');

    }

    public function changeStatus($id)
    {
        $country = Country::query()->find($id);
        if (is_null($country) || $country->is_deleted != 0) {
            return $this->sendError("الدولة غير موجودة", "country is't exists");
        }
        if ($country->status === 'active') {
            $country->update(['status' => 'not_active']);
        } else {
            $country->update(['status' => 'active']);
        }
        $success['$countries'] = new CountryResource($country);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعدبل حالة الدولة بنجاح', 'country status updared successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(CountryRequest $request, $country)
    {
        $country = Country::query()->find($country);
        if (is_null($country) || $country->is_deleted != 0) {
            return $this->sendError("الدولة غير موجودة", "country is't exists");
        }
        
        $country->update([
            'name' => $request->input('name'),
            'name_en' => $request->input('name_en'),
            'code' => $request->input('code'),
        ]);
        //$country->fill($request->post())->update();
        $success['countriesd'] = new CountryResource($country);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        if (is_null($country) || $country->is_deleted != 0) {
            return $this->sendError("الدولة غير موجودة", "country is't exists");
        }

        $country->update(['is_deleted' => $country->id]);

        $success['country'] = new CountryResource($country);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الدولة بنجاح', 'country deleted successfully');

    }

    public function deleteAll(Request $request)
    {

        $countries = Country::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($countries) > 0) {
            foreach ($countries as $country) {

                $country->update(['is_deleted' => $country->id]);
                $success['$country'] = new CountryResource($country);
            }
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف المدينة بنجاح', 'city deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }

    }
    public function searchCountry(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $query = $request->input('query');
        $countries = Country::where('is_deleted', 0)
        ->where('name', 'like', "%$query%")->orderByDesc('created_at');
        $countries =$countries->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $countries->total();
        $success['page_count'] = $countries->lastPage();
        $success['current_page'] = $countries->currentPage();
        $success['countries'] = CountryResource::collection($countries);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المدن بنجاح', 'Country Information returned successfully');

    }
}
