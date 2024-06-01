<?php

namespace App\Http\Controllers\api\storeDashboard;

use Psr7\Utils;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Account;

use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Supplierdocument;
use App\Services\FatoorahServices;
use App\Http\Resources\PaymentResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\SupplierResource;
use App\Http\Controllers\api\BaseController as BaseController;

class SupplierController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        $supplier = new FatoorahServices();

      
        if (is_null($account)) {
            return $this->sendError("لا يوجد حساب بنكي", "Account is't exists");
        }
        $supplierCode = $supplier->buildRequest('v2/GetSupplierDetails?suppplierCode=' . $storeAdmain->supplierCode,'GET');
        // $supplierDeposits = $supplier->buildRequest('v2/GetSupplierDeposits?SupplierCode=' . $storeAdmain->supplierCode,'GET');
        $success['supplierUser'] = new SupplierResource($account);
        $success['SupplierDetails'] = $supplierCode;
        // $success['SupplierDeposits'] =  $supplierDeposits;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');

    }
    public function show()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        if (is_null($account)) {
            return $this->sendError("لا يوجد حساب بنكي", "Account is't exists");
        }
        $supplierdocument = Supplierdocument::where('store_id', auth()->user()->store_id)->whereNot('type' , 20)->get();
        $supplier = new FatoorahServices();
        $supplierCode = $supplier->buildRequest('v2/GetSupplierDetails?suppplierCode=' . $storeAdmain->supplierCode,'GET');
        // $supplierDocument = $supplier->buildRequest('v2/GetSupplierDocuments?SupplierCode=' . $storeAdmain->supplierCode,'GET');
        $success['supplierUser'] = new SupplierResource($account);
        $success['SupplierDetails'] = $supplierCode;
         $success['SupplierDocumentUser'] = $supplierdocument;
        // $success['SupplierDocument'] = $supplierDocument;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');
    }
    public function store(Request $request)
    {
       
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        // if ($account) {
        //     return $this->sendError(" الحساب البنكي موجود مسبقا ", "account is't exists");
        // }
 
        $data = [
            'SupplierName' => $store->owner_name,
            'Mobile' => str_replace("+", "00", $storeAdmain->phonenumber),
            'Email' => $storeAdmain->email,
            'DepositTerms' => 'Daily',
             'BankId' => $request->bankId,
            'BankAccountHolderName' => $request->bankAccountHolderName,
            'BankAccount' => $request->bankAccount,
            'Iban' => $request->iban,
            'BusinessName'=>$store->store_name,
            'logo'=>public_path('storage\images\storelogo') . '\\' . $store->logo_pure ,
            'DisplaySupplierDetails'=>true
        ];

       
        $supplier = new FatoorahServices();
        try{
        $supplierCode = $supplier->createSupplier('v2/CreateSupplier' ,$data);
        }
        catch(ClientException $e) {
            return $this->sendError("خطأ في البيانات المدخلة",'Message: ' .$e->getMessage());
         }
        if ($supplierCode->IsSuccess == false) {
            return $this->sendError("خطأ في البيانات", $supplierCode->FieldsErrors[0]->Error);
        }
        $account = Account::updateOrCreate(
            [
                'store_id' => auth()->user()->store_id,
            ], [
                'bankId' => $request->input('bankId'),
                'bankAccountHolderName' => $request->input('bankAccountHolderName'),
                'bankAccount' => $request->input('bankAccount'),
                'iban' => $request->input('iban'),
                'supplierCode' => $supplierCode->Data->SupplierCode,               'status' => 'active',
            ]);
        $storeAdmain->update([
            'supplierCode' => $supplierCode->Data->SupplierCode]);
      

        $arrays = array();
        if ($store->verification_type == "maeruf") {
            $file = $store->file;
            $type = 2;
        } else {
            $file = $store->file;
            $type = 20;
        }
        $arrays = [[$request->civil_id, 1],[$request->bankAccountLetter, 21], [$request->website_image, 25], [$file, $type]];
        foreach ($arrays as $file) {
            if (is_uploaded_file($file[0])) {
                $supplier = new FatoorahServices();
                $imageName = Str::random(10) . time() . '.' . $file[0]->getClientOriginalExtension();
                $filePath = 'images/storelogo/' . $imageName;
                $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($file[0]));
                if ($isFileUploaded) {
                    $url = asset('storage/images/storelogo') . '/' . $imageName;
                }
            } else {
                $url = $file[0];
            }
            $data = [
                'FileUpload' => $url,
                'FileType' => $file[1],
                'SupplierCode' => $account->supplierCode,
            ];
            if ($data['FileUpload'] != null) {
                $supplierDocument = $supplier->uploadSupplierDocument('v2/UploadSupplierDocument', $data);
                $supplierdocument = Supplierdocument::updateOrCreate(
                    [
                        'supplierCode' => $account->supplierCode,
                        'store_id' => auth()->user()->store_id,
                        'type' => $file[1],
                    ],
                    ['file' => $url,
                    ]
                );
            }
        }

        $success['supplierUser'] = new SupplierResource($account);
        $success['supplierDocument'] = Supplierdocument::where('store_id', auth()->user()->store_id)->get();
        $success['status'] = 200;

        return $this->sendResponse($success, 'سيتم مراجعه البيانات المدخلة وعند الموافقة تصلك رسالة على الايميل', 'insert successfully');
    }
    public function update(Request $request)
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();

        if (!$account) {
            return $this->sendError(" لا يوجد حساب بنكي", "account is't exists");
        }
        $data = [
            'SupplierName' => $store->owner_name,
            'SupplierCode' => $storeAdmain->supplierCode,
            'Mobile' => str_replace("+", "00", $storeAdmain->phonenumber),
            'Email' => $storeAdmain->email,
            'BankId' => $request->bankId,
            'BankAccountHolderName' => $request->bankAccountHolderName,
            'BankAccount' => $request->bankAccount,
            'Iban' => $request->iban,
        ];
        $supplier = new FatoorahServices();
        $supplierCode = $supplier->buildRequest('v2/EditSupplier','POST',json_encode($data));
        if ($supplierCode['IsSuccess'] == false) {
            return $this->sendError("خطأ في البيانات", $supplierCode->FieldsErrors[0]->Error);
        }
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        $account->update([
            'bankId' => $request->input('bankId'),
            'bankAccountHolderName' => $request->input('bankAccountHolderName'),
            'bankAccount' => $request->input('bankAccount'),
            'iban' => $request->input('iban'),
            'supplierCode' => $supplierCode['Data']['SupplierCode'],
        ]);
        $storeAdmain->update(['supplierCode' => $supplierCode['Data']['SupplierCode']]);

        $arrays = array();
        if ($store->verification_type == "maeruf") {
            $file = $store->file;
            $type = 2;
        } else {
            $file = $store->file;
            $type = 20;
        }
        $arrays = [[$request->civil_id, 1], [$file, $type], [$request->bankAccountLetter, 21], [$request->website_image, 25]];
        foreach ($arrays as $file) {
            if (is_uploaded_file($file[0])) {
                $supplier = new FatoorahServices();
                $imageName = Str::random(10) . time() . '.' . $file[0]->getClientOriginalExtension();
                $filePath = 'images/storelogo/' . $imageName;
                $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($file[0]));
                if ($isFileUploaded) {
                    $url = asset('storage/images/storelogo') . '/' . $imageName;
                }
            } else {
                $url = $file[0];
            }
            $data = [
                'FileUpload' => $url,
                'FileType' => $file[1],
                'SupplierCode' => $account->supplierCode,
            ];
            if ($data['FileUpload'] != null) {
                $supplierDocument = $supplier->uploadSupplierDocument('v2/UploadSupplierDocument', $data);
                $supplierdocument = Supplierdocument::updateOrCreate(
                    [
                        'supplierCode' => $account->supplierCode,
                        'store_id' => auth()->user()->store_id,
                        'type' => $file[1],
                    ],
                    ['file' => $url,
                    ]
                );
            }
        }
        $supplierDocument = $supplier->buildRequest('v2/GetSupplierDocuments?suppplierCode=' .$storeAdmain->supplierCode,'GET');

        $success['supplierUserDocument'] =$supplierDocument;
        $success['supplierUser'] = new SupplierResource($account);
        $success['supplierDocument'] = Supplierdocument::where('store_id', auth()->user()->store_id)->get();
        $success['status'] = 200;

        return $this->sendResponse($success, 'سيتم مراجعه البيانات المدخلة وعند الموافقة تصلك رسالة على الايميل', 'update successfully');
    }
    public function showSupplierDashboard()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();

        if (!$account) {
            return $this->sendError(" لا يوجد حساب بنكي", "account is't exists");
        }
        $supplier = new FatoorahServices();
        $supplierCode = $supplier->buildRequest('v2/GetSupplierDashboard?SupplierCode=' . $storeAdmain->supplierCode,'GET');
        // if ( $supplierCode->IsSuccess == false){
        //    return $this->sendError("خطأ في البيانات",$supplierCode->FieldsErrors[0]->Error);
        // }
        $success['SupplierDashboard'] = $supplierCode;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');
    }

    public function uploadSupplierDocument(Request $request)
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        $supplier = new FatoorahServices();
        $imageName = Str::random(10) . time() . '.' . $request->FileUpload->getClientOriginalExtension();
        $filePath = 'images/storelogo/' . $imageName;
        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->FileUpload));
        if ($isFileUploaded) {
            $request->FileUpload = asset('storage/images/storelogo') . '/' . $imageName;
        }

        $data = [
            'FileUpload' => $request->FileUpload,
            'FileType' => $request->type,
            'SupplierCode' => $account->supplierCode,
        ];

        $supplierDocument = $supplier->uploadSupplierDocument('v2/UploadSupplierDocument', $data);
        $supplierdocument = Supplierdocument::updateOrCreate(
            [
                'supplierCode' => $account->supplierCode,
                'store_id' => auth()->user()->store_id,
            ],
            ['file' => $request->FileUpload,
                'type' => $request->type]
        );

        $success['SupplierDocument'] = $supplierDocument;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');
    }

    public function billing(Request $request)
    {
        $ids=Order::where('store_id', auth()->user()->store_id)->where('payment_status','paid')->pluck('id')->toArray();
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $payments=Payment::where('store_id', auth()->user()->store_id)->wherein('orderID',$ids)->orderByDesc('created_at')->paginate($count);
        $success['page_count'] = $payments->lastPage();
        $success['current_page'] = $payments->currentPage();
        $success['billing'] = PaymentResource::collection($payments);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الفواتير', ' show successfully');
    }
    public function showBilling($id)
    {
        $ids=Order::where('store_id', auth()->user()->store_id)->where('payment_status','paid')->pluck('id')->toArray();
        $payment=Payment::where('store_id', auth()->user()->store_id)->wherein('orderID',$ids)->where('id',$id)->orderByDesc('created_at')->first();
        $payments =new PaymentResource($payment);
        $success['billing'] = $payments;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الفاتورة بنجاح', ' show successfully');
    }

}
