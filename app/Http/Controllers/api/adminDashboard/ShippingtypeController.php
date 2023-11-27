<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ShippingtypeResource;
use App\Models\Shippingtype;
use Illuminate\Http\Request;

class ShippingtypeController extends BaseController
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

        $success['shippingtypes'] = ShippingtypeResource::collection(Shippingtype::where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع شركات الشحن بنجاح', 'Shippingtype return successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */

    public function changeStatus($id)
    {
        $shippingtype = Shippingtype::query()->find($id);
        if (is_null($shippingtype) || $shippingtype->is_deleted != 0) {
            return $this->sendError("شركة الشحن غير موجودة", "shippingtype is't exists");
        }

        if ($shippingtype->status === 'active') {
            $shippingtype->update(['status' => 'not_active']);
            $shippingtype->stores()->detach();
        } else {
            $shippingtype->update(['status' => 'active']);
        }
        $success['shippingtypes'] = new ShippingtypeResource($shippingtype);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة شركة الدفع بنجاح', 'shipping type updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shippingtype  $shippingtype
     * @return \Illuminate\Http\Response
     */
    public function wallet()
    {
        $key = array(
            'userId' => env('GOTEX_UserId_KEY'),
            'apiKey' => env('GOTEX_API_KEY'),
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/user/get-user-data',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => json_encode($key),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $success['Wallet'] = json_decode($response);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المحفظة', 'wallet show successfully');
    }

}
