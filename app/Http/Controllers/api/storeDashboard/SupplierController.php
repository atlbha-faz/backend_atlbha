<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\SupplierResource;
use App\Models\Account;
use App\Models\Store;
use App\Models\Supplierdocument;
use App\Models\User;
use App\Services\FatoorahServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $supplierCode = $supplier->getSupplierDashboard('/v2/GetSupplierDetails?suppplierCode=' . $storeAdmain->supplierCode);

        if (is_null($account)) {
            return $this->sendError("لا يوجد حساب بنكي", "Account is't exists");
        }
        $success['supplierUser'] = new SupplierResource($account);
        $success['SupplierDetails'] = $supplierCode;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');

    }
    public function show()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        $supplierdocument = Supplierdocument::where('store_id', auth()->user()->store_id)->get();
        $supplier = new FatoorahServices();
        $supplierCode = $supplier->getSupplierDashboard('/v2/GetSupplierDetails?suppplierCode=' . $storeAdmain->supplierCode);
        $supplierDocument = $supplier->getSupplierDashboard('/v2/GetSupplierDocuments?suppplierCode=' . $storeAdmain->supplierCode);
        $success['supplierUser'] = new SupplierResource($account);
        $success['SupplierDetails'] = $supplierCode;
        $success['SupplierDocumentUser'] = $supplierdocument;
        $success['SupplierDocument'] = $supplierDocument;

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');
    }
    public function store(Request $request)
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $store = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $account = Account::where('store_id', auth()->user()->store_id)->first();
        if ($account) {
            return $this->sendError(" الحساب البنكي موجود مسبقا ", "account is't exists");
        }
        $data = [
            'SupplierName' => $store->owner_name,
            'Mobile' => $storeAdmain->phonenumber,
            'Email' => $storeAdmain->email,
            'CommissionPercentage' => 0.1,
            'CommissionValue' => 0,
            'DepositTerms' => 'Daily',
            'BankId' => $request->bankId,
            'BankAccountHolderName' => $request->bankAccountHolderName,
            'BankAccount' => $request->bankAccount,
            'Iban' => $request->iban,
        ];

        $supplier = new FatoorahServices();
        $supplierCode = $supplier->createSupplier('v2/CreateSupplier', $data);
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
                'supplierCode' => $supplierCode->Data->SupplierCode,
                'status' => 'active',
            ]);
        $storeAdmain->update([
            'supplierCode' => $supplierCode->Data->SupplierCode]);
        // $success['supplier'] = $supplierCode;

        $arrays = array();
        if ($store->verification_type == "maeruf") {
            $file = $store->file;
            $type = 2;
        } else {
            $file = $store->file;
            $type = 20;
        }
        $arrays = [[$request->civil_id, 1], [$file, 20], [$request->bankAccountLetter, 21], [$request->website_image, 25]];
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

        return $this->sendResponse($success, 'تم الإضافة بنجاح', 'insert successfully');
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
            'Mobile' => $storeAdmain->phonenumber,
            'Email' => $storeAdmain->email,
            'BankId' => $request->bankId,
            'BankAccountHolderName' => $request->bankAccountHolderName,
            'BankAccount' => $request->bankAccount,
            'Iban' => $request->iban,
        ];
        $supplier = new FatoorahServices();
        $supplierCode = $supplier->createSupplier('v2/EditSupplier', $data);
        if ($supplierCode->IsSuccess == false) {
            return $this->sendError("خطأ في البيانات", $supplierCode->FieldsErrors[0]->Error);
        }
        $account = Account::where('store_id', auth()->user()->store_id)->where('status', 'active')->first();
        $account->update([
            'bankId' => $request->input('bankId'),
            'bankAccountHolderName' => $request->input('bankAccountHolderName'),
            'bankAccount' => $request->input('bankAccount'),
            'iban' => $request->input('iban'),
            'supplierCode' => $supplierCode->Data->SupplierCode,
        ]);
        $storeAdmain->update(['supplierCode' => $supplierCode->Data->SupplierCode]);

        $arrays = array();
        if ($store->verification_type == "maeruf") {
            $file = $store->file;
            $type = 2;
        } else {
            $file = $store->file;
            $type = 20;
        }
        $arrays = [[$request->civil_id, 1], [$file, 20], [$request->bankAccountLetter, 21], [$request->website_image, 25]];
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

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'update successfully');
    }
    public function showSupplierDashboard()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $supplier = new FatoorahServices();
        $supplierCode = $supplier->getSupplierDashboard('v2/GetSupplierDashboard?SupplierCode=' . $storeAdmain->supplierCode);
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
        $account = Account::where('store_id', auth()->user()->store_id)->where('status', 'active')->first();
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

}
