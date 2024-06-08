<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
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
    public function index(Request $request)
    {      
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data=City::where('is_deleted',0)->orderByDesc('created_at');
        $data= $data->paginate($count);
        $success['cities']=CityResource::collection($data);
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المدن بنجاح','cities return successfully');

    }

    /**
     * Show the form for creating a new resource
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
    public function store(CityRequest $request)
    {

        $input = $request->all();
        $city = City::create([
            'name' => $request->name,
            'name_en'=>$request->name_en,
            'code' =>$request->code,
            'country_id' => $request->country_id,
          ]);

         $success['cities']=New CityResource($city);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة مدينة بنجاح','City Added successfully');
    }



    public function show(City $city)
    {
         $city = City::query()->find($city->id);
         if (is_null($city) || $city->is_deleted !=0){
         return $this->sendError("المدينه غير موجودة","city is't exists");
         }


        $success['cities']=New CityResource($city);
        $success['status']= 200;

         return $this->sendResponse($success,'تم  عرض بنجاح','city showed successfully');

    }
    public function changeStatus($id)
    {
        $city = City::query()->find($id);
         if (is_null($city) ||$city->is_deleted !=0){
         return $this->sendError("المدينه غير موجودة","city is't exists");
         }

        if($city->status === 'active'){
        $city->update(['status' => 'not_active']);
        }
        else{
        $city->update(['status' => 'active']);
        }
        $success['cities']=New CityResource($city);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة المدينه بنجاح','city updated successfully');

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, $city)
{
    $city =  City::where('id', $city)->first();
        if (is_null($city) || $city->is_deleted !=0){
         return $this->sendError("المدينه غير موجودة","city is't exists");
    }
         $input = $request->all();
    
        $city->update([
            'name' => $request->input('name'),
            'name_en' => $request->input('name_en'),
            'code' => $request->input('code'),
            'country_id' => $request->input('country_id')
        ]);

        $success['cities']=New CityResource($city);
        $success['status']= 200;

         return $this->sendResponse($success,'تم التعديل بنجاح','City updated successfully');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
         if (is_null($city) ||$city->is_deleted !=0){
         return $this->sendError("المدينه غير موجودة","city is't exists");
         }
        $city->update(['is_deleted' =>$city->id ]);

        $success['cities']=New CityResource($city);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف المدينة بنجاح','city deleted successfully');
    }

      public function deleteAll(Request $request)
    {

            $citys =city::whereIn('id',$request->id)->where('is_deleted',0)->get();
            if(count($citys)>0){
           foreach($citys as $city)
           {
             $city->update(['is_deleted' => $city->id]);
            $success['citys']=New cityResource($city);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف المدينة بنجاح','city deleted successfully');
        }
            else{
                $success['status']= 200;
             return $this->sendResponse($success,'المدخلات غيرموجودة','id is not exit');
              }
    }
    public function searchCity(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $query = $request->input('query');
        $cities = City::where('is_deleted', 0)
        ->where('name', 'like', "%$query%")->orderByDesc('created_at');
        $cities = $cities->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $cities->total();
        $success['page_count'] = $cities->lastPage();
        $success['current_page'] = $cities->currentPage();
        $success['cities'] = cityResource::collection($cities);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المدن بنجاح', 'city Information returned successfully');

    }

}
