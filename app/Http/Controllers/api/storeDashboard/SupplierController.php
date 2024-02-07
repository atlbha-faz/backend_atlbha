<?php

namespace App\Http\Controllers\api\storeDashboard;
use App\Models\User;
use App\Models\Store;
use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Supplierdocument;
use App\Services\FatoorahServices;
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

    }
    public function show()
    {
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $account=Account::where('store_id', auth()->user()->store_id)->first();
        $success['supplierUser'] = new SupplierResource($account);
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
     $account=Account::create([
            'bankId' => $request->input('bankId'),
            'bankAccountHolderName' => $request->input('bankAccountHolderName'),
            'bankAccount' => $request->input('bankAccount'),
            'iban' => $request->input('iban'),
            'supplierCode' =>  $supplierCode->Data->SupplierCode,
            'store_id'=>auth()->user()->store_id,
            'status'=>'active'
        ]);
        $storeAdmain->update([
            'supplierCode' =>  $supplierCode->Data->SupplierCode]);
        // $success['supplier'] = $supplierCode;
        $success['supplierUser'] = new SupplierResource($account);
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
     $account=Account::where('store_id', auth()->user()->store_id)->where('status', 'active')->first();
     $account->update([
            'bankId' => $request->input('bankId'),
            'bankAccountHolderName' => $request->input('bankAccountHolderName'),
            'bankAccount' => $request->input('bankAccount'),
            'iban' => $request->input('iban'),
            'supplierCode' =>  $supplierCode->Data->SupplierCode
        ]);
        $storeAdmain->update(['supplierCode' =>$supplierCode->Data->SupplierCode]);

     $supplier=new FatoorahServices();
     $supplierCode  =$supplier->createSupplier('v2/EditSupplier', $data);
     if ( $supplierCode->IsSuccess == false){
        return $this->sendError("خطأ في البيانات",$supplierCode->FieldsErrors[0]->Error);
     }
     

        // $success['supplier'] = $supplierCode;
        $success['supplierUser'] = new SupplierResource( $account);
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

    public function uploadSupplierDocument(Request $request){
        $storeAdmain = User::where('user_type', 'store')->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->first();
        $account=Account::where('store_id', auth()->user()->store_id)->where('status', 'active')->first();
        $supplier=new FatoorahServices();
        $imageName = Str::random(10) . time() . '.' . $request->FileUpload->getClientOriginalExtension();
        $filePath = 'images/storelogo/' . $imageName;
        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->FileUpload));
        if ($isFileUploaded) {
            $request->FileUpload = asset('storage/images/storelogo') . '/' .$imageName;
        }
 
        $data = [
            'FileUpload'=> $request->FileUpload,
            'FileType' => $request->type,
            'SupplierCode' =>$account->supplierCode
        ];
  
        $supplierDocument  =$supplier->uploadSupplierDocument('v2/UploadSupplierDocument',$data);
        $supplierdocument=Supplierdocument::updateOrCreate(
        [
        'supplierCode'=>$account->supplierCode,
        'store_id'=>auth()->user()->store_id
          ],
        ['file'=>$request->FileUpload,
         'type'=>$request->type,]
         );
   
        $success['SupplierDocument'] =  $supplierDocument;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بيانات الحساب البنكي بنجاح', ' show successfully');
    }

}
