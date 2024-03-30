<?php

namespace App\Http\Controllers\api\adminDashboard;

use Illuminate\Http\Request;
use App\Models\AtlobhaContact;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\atlobhaContactResource;
use App\Http\Controllers\api\BaseController as BaseController;

class AtlobhaContactController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $success['atlobhaContact']=atlobhaContactResource::collection(AtlobhaContact::where('is_deleted',0)->orderByDesc('created_at')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الرسائل  بنجاح','atlobhaContact return successfully');
    }



    public function show($contact)
    {
        $atlobhaContact= AtlobhaContact::query()->find($contact);
        if (is_null($atlobhaContact) ||$atlobhaContact->is_deleted !=0){
            return $this->sendError("طلب الدعم غير موجود", "atlobhaContact is't exists");
               }
             $success['atlobhaContacts'] = new atlobhaContactResource($atlobhaContact);
              $success['status']= 200;


        return $this->sendResponse($success, 'تم عرض الطلب بنجاح', 'atlobhaContact showed successfully');
    }

    public function changeStatus($id)
    {
        $atlobhaContact = AtlobhaContact::query()->find($id);
        if (is_null($atlobhaContact) || $atlobhaContact->is_deleted != 0) {
            return $this->sendError("طلب الدعم غير موجود", "atlobhaContact is't exists");
        }

        if ($atlobhaContact->status === 'not_finished') {
            $atlobhaContact->update(['status' => 'finished']);
        } else {
            $atlobhaContact->update(['status' => 'not_finished']);
        }
        $success['atlobhaContacts'] = new atlobhaContactResource($atlobhaContact);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة الطلب بنجاح', 'atlobhaContact updated successfully');

    }
    public function deleteAll(Request $request)
    {


            $atlobhaContacts =AtlobhaContact::whereIn('id',$request->id)->where('is_deleted',0)->get();
            if(count($atlobhaContacts)>0){
           foreach($atlobhaContacts as $atlobhaContact)
           {

               $atlobhaContact->update(['is_deleted' => $atlobhaContact->id]);
              $success['atlobhaContacts']= New atlobhaContactResource($atlobhaContact);

            }
               $success['status']= 200;
                return $this->sendResponse($success,'تم حذف الرسائل بنجاح','atlobhaContact deleted successfully');
           }

      else{
          $success['status']= 200;
       return $this->sendResponse($success,'المدخلات غيرموجودة','id is not exit');
        }

     }
}
