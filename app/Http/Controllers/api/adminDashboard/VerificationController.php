<?php

namespace App\Http\Controllers\api\adminDashboard;
use App\Models\Note;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Events\VerificationEvent;
use App\Http\Resources\NoteResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\VerificationResource;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verificationNotification;
use App\Http\Controllers\api\BaseController as BaseController;

class VerificationController extends BaseController
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

        $success['stores']=VerificationResource::collection(Store::where('is_deleted',0,)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المتاجر بنجاح','Stores return successfully');
    }
    public function acceptVerification($id)
    {
        $store = Store::query()->find($id);
         if (is_null($store) || $store->is_deleted==1){
         return $this->sendError("المتجر غير موجود","store is't exists");
         }

        $store->update(['verification_status' => 'accept']);
        $users = User::where('store_id', $store->id)->get();
        $data = [
            'message' => ' تم قبول توثيق المتجر',
            'store_id' =>$store->id,
            'user_id'=>auth()->user()->id,
            'type'=>"store_request",
            'object_id'=>$store->id
        ];
       
        foreach($users as $user)
        {
        Notification::send($user, new verificationNotification($data));
        }
        
        event(new VerificationEvent($data));
        $success['store']=New VerificationResource($store);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة المتجر بنجاح','store updated successfully');

    }

      public function rejectVerification($id)
    {
        $store = Store::query()->find($id);
         if (is_null($store) || $store->is_deleted==1){
         return $this->sendError("المتجر غير موجود","store is't exists");
         }

        $store->update(['verification_status' => 'reject']);
        $users = User::where('store_id', $store->id)->get();
        $data = [
            'message' => ' تم رفض توثيق المتجر',
            'store_id' =>$store->id,
            'user_id'=>auth()->user()->id,
            'type'=>"store_request",
            'object_id'=>$store->id
        ];
       
        foreach($users as $user)
        {
        Notification::send($user, new verificationNotification($data));
        }
        
        event(new VerificationEvent($data));
        $success['store']=New VerificationResource($store);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة المتجر بنجاح','store updated successfully');

    }
   /* public function destroy($store)
    {
       $store = Store::query()->find($store);
         if (is_null($store) || $store->is_deleted==1){
         return $this->sendError("المتجر غير موجود","store is't exists");
         }
        $store->update(['is_deleted' => 1]);

        $success['store']=New VerificationResource($store);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف المتجر بنجاح','store deleted successfully');
    }*/

    public function addNote(Request $request)
     {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'subject'=>'required|string|max:255',
            'details'=>'required|string',
            'store_id'=>'required'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $note = Note::create([
            'subject' => $request->subject,
            'details' => $request->details,
            'store_id' => $request->store_id,
            'product_id'=>null
          ]);


         $success['notes']=New NoteResource($note );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة ملاحظة بنجاح','note Added successfully');
    }

    public function verification_update(Request $request)
    {
      $store = Store::query()->find($request->store_id);
        $input = $request->all();
        $validator =  Validator::make($input ,[
           'activity_id' =>'required|array',
           'store_name'=>'required|string',
           'link'=>'required|url',
           'file'=>'required|mimes:pdf,doc,excel',
           'name'=>'required|string|max:255',
           'store_id'=>'required'
        ]);
        if ($validator->fails())
        {
           # code...
           return $this->sendError(null,$validator->errors());
        }

            $users = User::where('store_id', $request->store_id)->where('user_type','store')->get();
  
            $data = [
                'message' => 'تعديل توثيق',
                'store_id' =>  $request->store_id,
                'user_id'=>auth()->user()->id,
                'type'=>"store_verified",
                'object_id'=> $request->store_id
            ];
            foreach($users as $user)
            {
            Notification::send($user, new verificationNotification($data));
            }
            event(new VerificationEvent($data));
        $store->update([
            'store_name' =>  $request->input('store_name'),
            'link' =>  $request->input('link'),
            'file' =>  $request->input('file'),

        ]);

         $store->activities()->sync($request->activity_id);
       $user = User::where('is_deleted',0)->where('store_id',$request->store_id)->where('user_type','store')->first();
       $user->update([
         'name' =>  $request->input('name'),
         ]);
         

        $success['store']=Store::where('is_deleted',0)->where('id',$request->store_id)->first();
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل المتجر بنجاح','store update successfully');
    }
    public function deleteall(Request $request)
    {

            $stores =Store::whereIn('id',$request->id)->get();
           foreach($stores as $store)
           {
            if (is_null($store) || $store->is_deleted==1){
                   return $this->sendError("المتجر غير موجودة"," store is't exists");
             }
               $store->update([
            'link' =>  null,
            'file' =>  null,
               'verification_status'=>'pending'
               ]);
            }
               $success['stores']= VerificationResource::collection($stores);
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف المتجر بنجاح','store deleted successfully');
    }
}
