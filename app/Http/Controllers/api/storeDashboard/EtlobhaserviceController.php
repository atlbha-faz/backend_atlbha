<?php

namespace App\Http\Controllers\api\storeDashboard;

use Notification;
use App\Models\User;
use App\Models\Store;
use App\Models\Service;
use App\Models\Paymenttype;
use App\Models\Websiteorder;
use Illuminate\Http\Request;
use App\Events\VerificationEvent;
use App\Services\FatoorahServices;
use App\Http\Resources\UserResource;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WebsiteorderResource;
use App\Notifications\verificationNotification;
use App\Http\Controllers\api\BaseController as BaseController;

class EtlobhaserviceController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {

        $success['stores'] = new StoreResource(Store::where('is_deleted', 0, )->where('id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Stores return successfully');
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'service_id' => 'required|array|exists:services,id',
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'paymentype_id' => 'required|exists:paymentypes,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $order_number = Websiteorder::orderBy('id', 'desc')->first();
        if (is_null($order_number)) {
            $number = 0001;
        } else {

            $number = $order_number->order_number;
            $number = ((int) $number) + 1;
        }
        $totalPrice = Service::whereIn('id', $request->service_id)->sum('price');
        $websiteorder = Websiteorder::create([
            'type' => 'service',
            'order_number' => str_pad($number, 4, '0', STR_PAD_LEFT),
            'store_id' => auth()->user()->store_id,
            'total_price' => $totalPrice,
        ]);
        if ($request->has('name') && $request->name != null) {
            $service = Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'not_active',
            ]);
            $array1 = array($service->id);
            if ($request->service_id != null) {
                $result = array_merge($request->service_id, $array1);
            } else {
                $result = $array1;
            }
        } else {
            if ($request->service_id != null) {
                $result = $request->service_id;
            }
        }
        if ($result != null) {
            $websiteorder->services()->attach($result);
        }
        $paymentype = Paymenttype::where('id', $request->paymentype_id)->first();
        if (in_array($request->paymentype_id, [1, 2])) {

            $processingDetails = [
                "AutoCapture" => true,
                "Bypass3DS" => false,
            ];
            $processingDetailsobject = (object) ($processingDetails);
        
            if ($totalPrice == 0) {
                return $this->sendError("يجب ان يكون المبلغ اكبر من الصفر", "price must be more than zero");
            }
            $data = [
                "PaymentMethodId" => $paymentype->paymentMethodId,
                "CustomerName" => (auth()->user()->name != null ? auth()->user()->name : auth()->user()->store->store_name.'('.auth()->user()->user_name.')'),
                "InvoiceValue" => $websiteorder->total_price, // total_price
                "CustomerEmail" => auth()->user()->email,
                "CustomerMobile"=>substr(auth()->user()->phonenumber, 4),
                "CallBackUrl" => 'https://store.atlbha.com/checkout-packages/success',
                "ErrorUrl" => 'https://store.atlbha.com/checkout-packages/failed',
                "Language" => 'AR',
                "DisplayCurrencyIso" => 'SAR',
                "ProcessingDetails" => $processingDetailsobject,
            ];
            $data = json_encode($data);
            $payment_process = new FatoorahServices();
            $response = $payment_process->buildRequest('v2/ExecutePayment', 'POST', $data);

            if (isset($response['IsSuccess'])) {
                if ($response['IsSuccess'] == true) {

                    $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                    $success['payment'] = $response;
                    $websiteorder->update([
                        'payment_method' => $paymentype->name,
                        'paymentTransectionID' => $InvoiceId,
                    ]);

                } else {
                    $success['payment'] = $response;
                }
            } else {
                $success['payment'] = $response;
            }
        } else {

            $websiteorder->update([
                'payment_method' => $paymentype->name,
                'paymentTransectionID' => $request->service_reference,
            ]);
        }
       
        

        $success['Websiteorders'] = new WebsiteorderResource($websiteorder);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الطلب بنجاح', 'Websiteorder Added successfully');
    }
    public function marketerRequest(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $users = User::where('is_deleted', 0)->where('user_type', "marketer");
        if ($request->has('id')) {
            $users = $users->where('city_id', $request->id);
        }
        $users = $users->paginate($count);
        $marketers = UserResource::collection($users);
        $success['page_count'] = $marketers->lastPage();
        $success['current_page'] = $marketers->currentPage();
        $success['marketers'] = $marketers;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المندوبين بنجاح', 'marketer return successfully');

    }
}
