<?php

namespace App\Http\Controllers\api\homePages;

use Illuminate\Http\Request;
use App\Models\AtlobhaContact;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\atlobhaContactResource;
use App\Http\Controllers\api\BaseController as BaseController;

class AtlobhaContactController extends BaseController
{
    public function index()
    {
        $success['atlobhaContact']=atlobhaContactResource::collection(AtlobhaContact::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الرسائل  بنجاح','atlobhaContact return successfully');
    }
    public function store(Request $request)
    {

        $input = $request->all();
        $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',
            'email'=>'required|email',
            'title'=>'required|string',
            'content' =>'required|string',
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $atlobhaContact = AtlobhaContact::create([
            'name' => $request->name,
            'email'=>$request->email,
            'title' =>$request->title,
            'content' =>$request->content,
        ]);
        $success['atlobhaContact']= New atlobhaContactResource($atlobhaContact);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة الرسالة  بنجاح','message Added successfully');
    }
    public function deleteAll(Request $request)
    {

        
            $atlobhaContacts =AtlobhaContact::whereIn('id',$request->id)
            -> where('is_deleted',0)->get();
            if(count($atlobhaContacts)>0){
           foreach($atlobhaContacts as $atlobhaContact)
           {
        
               $atlobhaContact->update(['is_deleted' => 1]);
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
