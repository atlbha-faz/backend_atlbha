<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
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
    public function index(Request $request)
    {    
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data=Client::where('is_deleted', 0)->orderByDesc('created_at');
        $data= $data->paginate($count);
        $success['clients'] = ClientResource::collection($data);
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع العملاء بنجاح', 'clients return successfully');

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
    public function store(ClientRequest $request)
    {
        $input = $request->all();
        $client = Client::create([
            'ID_number' => $request->ID_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'phonenumber' => $request->phonenumber,
            'image' => $request->image,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,

        ]);

        // return new CountryResource($country);
        $success['clients'] = new ClientResource($client);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة عميل بنجاح', 'client Added successfully');
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
        if (is_null($client) || $client->is_deleted != 0) {
            return $this->sendError("المندوب غير موجودة", "client is't exists");
        }
        $success['$clients'] = new ClientResource($client);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم  عرض بنجاح', 'client showed successfully');

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
    public function update(ClientRequest $request, $client)
    {
        $client = Client::where('id', $client)->first();
        if (is_null($client) || $client->is_deleted != 0) {
            return $this->sendError("المندوب غير موجودة", "client is't exists");
        }

        $input = $request->all();
       
        $client->update([
            'ID_number' => $request->input('ID_number'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'phonenumber' => $request->input('phonenumber'),
            'image' => $request->input('image'),
            'country_id' => $request->input('country_id'),
            'city_id' => $request->input('city_id'),

        ]);

        $success['clients'] = new ClientResource($client);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'modify  successfully');

    }

    public function changeStatus($id)
    {
        $client = Client::query()->find($id);
        if (is_null($client) || $client->is_deleted != 0) {
            return $this->sendError("العميل غير موجودة", "client is't exists");
        }
        if ($client->status === 'active') {
            $client->update(['status' => 'not_active']);
        } else {
            $client->update(['status' => 'active']);
        }
        $success['$clients'] = new ClientResource($client);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعدبل حالة العميل بنجاح', 'client status updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */

    public function destroy($client)
    {
        $client = Client::query()->find($client);
        if (is_null($client) || $client->is_deleted != 0) {
            return $this->sendError("المندوب غير موجودة", "client is't exists");
        }
        $client->update(['is_deleted' => $client->id]);

        $success['clients'] = new ClientResource($client);

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف العميل  بنجاح', 'clients deleted successfully');

    }
}
