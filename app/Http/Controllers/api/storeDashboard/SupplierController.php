<?php

namespace App\Http\Controllers\api\storeDashboard;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Services\FatoorahServices;
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

    }
    public function show()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $success['supplierUser'] = new SupplierResource($storeAdmain);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');
    }
    public function store(Request $request)
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $store= Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $data = [
         'SupplierName' =>$store->owner_name,
         'Mobile' =>$storeAdmain->phonenumber,
         'Email' =>$storeAdmain->email,
         'CommissionPercentage'=>0.1,
         'CommissionValue'=>0,
         'DepositTerms'=>'Daily',
         'BankId' => $request->bankId,
         'BankAccountHolderName' => $request->bankAccountHolderName,
         'BankAccount' => $request->bankAccount,
          'Iban' => $request->iban,
     ];

     $supplier=new FatoorahServices();
     $supplierCode  =$supplier->createSupplier('v2/CreateSupplier', $data);
     if ( $supplierCode->IsSuccess == false){
        return $this->sendError("خطأ في البيانات",$supplierCode->FieldsErrors[0]->Error);
     }
        $storeAdmain->update([
            'bankId' => $request->input('bankId'),
            'bankAccountHolderName' => $request->input('bankAccountHolderName'),
            'bankAccount' => $request->input('bankAccount'),
            'iban' => $request->input('iban'),
            'supplierCode' =>  $supplierCode->Data->SupplierCode
        ]);
        // $success['supplier'] = $supplierCode;
        $success['supplierUser'] = new SupplierResource($storeAdmain);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم الإضافة بنجاح', 'insert successfully');
    }
    public function update(Request $request)
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $store= Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $data = [
         'SupplierName' =>$store->owner_name,
         'SupplierCode'=>$storeAdmain->supplierCode,
         'Mobile' =>$storeAdmain->phonenumber,
         'Email' =>$storeAdmain->email,
         'BankId' => $request->bankId,
         'BankAccountHolderName' => $request->bankAccountHolderName,
         'BankAccount' => $request->bankAccount,
          'Iban' => $request->iban,
     ];

     $supplier=new FatoorahServices();
     $supplierCode  =$supplier->createSupplier('v2/EditSupplier', $data);
     if ( $supplierCode->IsSuccess == false){
        return $this->sendError("خطأ في البيانات",$supplierCode->FieldsErrors[0]->Error);
     }
        $storeAdmain->update([
            'bankId' => $request->input('bankId'),
            'bankAccountHolderName' => $request->input('bankAccountHolderName'),
            'bankAccount' => $request->input('bankAccount'),
            'iban' => $request->input('iban'),
            'supplierCode' =>  $supplierCode->Data->SupplierCode
        ]);
        // $success['supplier'] = $supplierCode;
        $success['supplierUser'] = new SupplierResource($storeAdmain);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'update successfully');
    }
    public function showSupplierDashboard()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $supplier=new FatoorahServices();
        $supplierCode  =$supplier->getSupplierDashboard('v2/GetSupplierDashboard?SupplierCode='.$storeAdmain->supplierCode);
        // if ( $supplierCode->IsSuccess == false){
        //    return $this->sendError("خطأ في البيانات",$supplierCode->FieldsErrors[0]->Error);
        // }
        $success['SupplierDashboard'] =  $supplierCode ;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');
    }
}
