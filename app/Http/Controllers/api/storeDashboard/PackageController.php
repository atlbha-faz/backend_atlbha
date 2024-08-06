<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\Package;
use App\Models\Package_store;
use App\Models\Paymenttype;
use App\Models\Store;
use App\Services\FatoorahServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function payment(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'paymentype_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        if (!is_null($request->package_id)) {
            $package = Package::where('id', $request->package_id)->first();
            $store->update([
                'package_id' => $request->package_id,
                'periodtype' => 'year',
            ]);
            $end_at = Carbon::now()->addYear()->format('Y-m-d H:i:s');
            $store->update([
                'start_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'end_at' => $end_at]);
            $store->packages()->attach($request->package_id, ['start_at' => Carbon::now()->format('Y-m-d H:i:s'), 'end_at' => $end_at, 'periodtype' => 'year']);

        } else {
            $package = Package::where('id', $store->package_id)->first();
        }
        $paymentype = Paymenttype::where('id', $request->paymentype_id)->first();
        if (in_array($request->paymentype_id, [1, 2])) {

            $processingDetails = [
                "AutoCapture" => true,
                "Bypass3DS" => false,
            ];
            $processingDetailsobject = (object) ($processingDetails);
            $data = [
                "PaymentMethodId" => $paymentype->paymentMethodId,
                "CustomerName" => (auth()->user()->name != null ? auth()->user()->name : auth()->user()->user_name),
                "InvoiceValue" => $package->yearly_price, // total_price
                "CustomerEmail" => auth()->user()->email,
                "CallBackUrl" => 'https://store.atlbha.com/Products/SouqOtlobha/success',
                "ErrorUrl" => 'https://store.atlbha.com/Products/SouqOtlobha/failed',
                "Language" => 'AR',
                "DisplayCurrencyIso" => 'SAR',
                "ProcessingDetails" => $processingDetailsobject,
            ];
            $data = json_encode($data);
            $supplier = new FatoorahServices();
            $response = $supplier->buildRequest('v2/ExecutePayment', 'POST', $data);

            if (isset($response['IsSuccess'])) {
                if ($response['IsSuccess'] == true) {

                    $InvoiceId = $response['Data']['InvoiceId']; // save this id with your order table
                    $success['payment'] = $response;
                    $payment = Package_store::where('store_id', $store->id)->where('package_id', $package->id)->orderBy('id', 'desc')->first();
                    $payment->update([
                        'paymentType' => $paymentype->name,
                        'paymentTransectionID' => $InvoiceId
                    ]);

                } else {
                    $success['payment'] = $response;
                }
            } else {
                $success['payment'] = $response;
            }

            $success['status'] = 200;
            return $this->sendResponse($success, 'تم ارسال الطلب بنجاح', 'order send successfully');

        }
    }
}
