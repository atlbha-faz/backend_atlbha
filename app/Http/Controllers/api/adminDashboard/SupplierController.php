<?php

namespace App\Http\Controllers\api\adminDashboard;

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
        $storeAdmain = User::where('user_type', 'admin')->where('is_deleted', 0)->where('store_id', null)->first();
        $account = Account::where('store_id', null)->first();
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
        $storeAdmain = User::where('user_type', 'admin')->where('is_deleted', 0)->where('store_id', null)->first();
        $account = Account::where('store_id', null)->first();
        if (is_null($account)) {
            return $this->sendError("لا يوجد حساب بنكي", "Account is't exists");
        }
        $supplierdocument = Supplierdocument::where('store_id', null)->whereNot('type' , 20)->get();
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
        $storeAdmain = User::where('user_type', 'admin')->where('is_deleted', 0)->where('store_id', null)->first();
        $account = Account::where('store_id', null)->first();
        if ($account) {
            return $this->sendError(" الحساب البنكي موجود مسبقا ", "account is't exists");
        }
        $data = [
            'SupplierName' => $storeAdmain->name,
            'Mobile' => str_replace("+", "00", $storeAdmain->phonenumber),
            'Email' => $storeAdmain->email,
            'DepositTerms' => 'Daily',
            'BankId' => $request->bankId,
            'BankAccountHolderName' => $request->bankAccountHolderName,
            'BankAccount' => $request->bankAccount,
            'Iban' => $request->iban,
        ];

        $supplier = new FatoorahServices();
        $supplierCode = $supplier->buildRequest('v2/CreateSupplier','POST', json_encode($data));
     
        if ($supplierCode['IsSuccess'] == false) {
            return $this->sendError("خطأ في البيانات", $supplierCode->FieldsErrors[0]->Error);
        }
        $account = Account::updateOrCreate(
            [
                'store_id' => null,
            ], [
                'bankId' => $request->input('bankId'),
                'bankAccountHolderName' => $request->input('bankAccountHolderName'),
                'bankAccount' => $request->input('bankAccount'),
                'iban' => $request->input('iban'),
                'supplierCode' =>$supplierCode['Data']['SupplierCode'],
                'status' => 'active',
            ]);
        $storeAdmain->update([
            'supplierCode' => $supplierCode['Data']['SupplierCode']]);
      

        $arrays = array();
      
        $arrays = [[$request->civil_id, 1],[$request->bankAccountLetter, 21], [$request->website_image, 25], [$request->file, 20]];
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
                        'store_id' => null,
                        'type' => $file[1],
                    ],
                    ['file' => $url,
                    ]
                );
            }
        }

        $success['supplierUser'] = new SupplierResource($account);
        $success['supplierDocument'] = Supplierdocument::where('store_id', null)->get();
        $success['status'] = 200;

        return $this->sendResponse($success, 'سيتم مراجعه البيانات المدخلة وعند الموافقة تصلك رسالة على الايميل', 'insert successfully');
    }
    public function update(Request $request)
    {
        $storeAdmain = User::where('user_type', 'admin')->where('is_deleted', 0)->where('store_id', null)->first();
        $account = Account::where('store_id', null)->first();

        if (!$account) {
            return $this->sendError(" لا يوجد حساب بنكي", "account is't exists");
        }
        $data = [
            'SupplierName' => $storeAdmain->name,
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
        if ($supplierCode['IsSuccess']== false) {
            return $this->sendError("خطأ في البيانات", $supplierCode->FieldsErrors[0]->Error);
        }
        $account = Account::where('store_id', null)->first();
        $account->update([
            'bankId' => $request->input('bankId'),
            'bankAccountHolderName' => $request->input('bankAccountHolderName'),
            'bankAccount' => $request->input('bankAccount'),
            'iban' => $request->input('iban'),
            'supplierCode' => $supplierCode['Data']['SupplierCode'],
        ]);
        $storeAdmain->update(['supplierCode' => $supplierCode['Data']['SupplierCode']]);

        $arrays = array();
      
        $arrays = [[$request->civil_id, 1], [$request->file, 20], [$request->bankAccountLetter, 21], [$request->website_image, 25]];
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
                        'store_id' => null,
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
        $success['supplierDocument'] = Supplierdocument::where('store_id', null)->get();
        $success['status'] = 200;

        return $this->sendResponse($success, 'سيتم مراجعه البيانات المدخلة وعند الموافقة تصلك رسالة على الايميل', 'update successfully');
    }
    public function showSupplierDashboard()
    {
        $storeAdmain = User::where('user_type', 'admin')->where('is_deleted', 0)->where('store_id', null)->first();
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
        $storeAdmain = User::where('user_type', 'admin')->where('is_deleted', 0)->where('store_id', null)->first();
        $account = Account::where('store_id', null)->first();
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
                'store_id' => null,
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
        $ids=Order::where('store_id', null)->where('payment_status','paid')->pluck('id')->toArray();
        $payments =PaymentResource::collection(Payment::where('store_id', null)->wherein('orderID',$ids)->orderByDesc('created_at')->get());
        $success['billing'] = $payments;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الفواتير', ' show successfully');
    }

}
