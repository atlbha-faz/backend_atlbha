<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class ClientController extends BaseController
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
      $success['clients']=ClientResource::collection(Client::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع العملاء بنجاح','clients return successfully');

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
    public function store(Request $request)
    {
         $input = $request->all();
        $validator =  Validator::make($input ,[
            'ID_number'=>'required|numeric',
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'email'=>'required|email|unique:clients',
            'gender'=>'required|in:male,female',
            'phonenumber' =>['required','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'image'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            // 'country_id'=>'required|exists:countries,id',
            'city_id'=>'required|exists:cities,id',


        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $client = Client::create([
            'ID_number'=> $request->ID_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'phonenumber' => $request->phonenumber,
            'image' => $request->image,
             'country_id' =>$request->country_id,
             'city_id' =>$request->city_id,
              'store_id'   => auth()->user()->store_id,

          ]);

         // return new CountryResource($country);
         $success['clients']=New ClientResource($client);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة عميل بنجاح','client Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($client)
    {
       $client = Client::query()->find($client);
       if (is_null($client) ||$client->is_deleted==1){
         return $this->sendError("المندوب غير موجودة","client is't exists");
         }
       views($client)->record();
        dd(views($client)->count());
        $success['$clients']=New ClientResource($client);
        $success['status']= 200;

         return $this->sendResponse($success,'تم  عرض بنجاح','client showed successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */


      public function changeStatus($id)
    {
        $client = Client::query()->find($id);
        if (is_null($client ) ||$client->is_deleted==1){
         return $this->sendError("العميل غير موجودة","client is't exists");
         }
        if($client->status === 'active'){
         $client->update(['status' => 'not_active']);
    }
      else{
      $client->update(['status' => 'active']);
          }
        $success['$clients']=New ClientResource($client);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعدبل حالة العميل بنجاح','client status updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */

    public function destroy($client)
    {
         $client =Client::query()->find($client);
       if (is_null($client ) ||$client->is_deleted==1){
         return $this->sendError("العميل غير موجودة","client is't exists");
         }
            $client->update(['is_deleted' => 1]);


         $success['clients']=New ClientResource($client);

        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف العميل  بنجاح','clients deleted successfully');

    }

       public function deleteall(Request $request)
    {

            $clients =Client::whereIn('id',$request->id)->get();
           foreach($clients as $client)
           {
             if (is_null($client) || $client->is_deleted==1 ){
                    return $this->sendError("العميل غير موجود","client is't exists");
             }
             $client->update(['is_deleted' => 1]);
            $success['clients']=New ClientResource($client);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف العميل بنجاح','client deleted successfully');
    }
}
