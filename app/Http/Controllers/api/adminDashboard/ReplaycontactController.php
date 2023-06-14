<?php

namespace App\Http\Controllers\api\adminDashboard;

use Illuminate\Http\Request;
use App\Models\Replaycontact;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReplaycontactResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ReplaycontactController extends BaseController
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
        $success['Replaycontacts']=ReplaycontactResource::collection(Replaycontact::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع ردود التواصل بنجاح','Replaycontacts return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'subject'=>'required|string|max:255',
            'message'=>'required|string',
            'contact_id'=>'required|exists:contacts,id'
        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $replaycontact = Replaycontact::create([
            'subject' => $request->subject,
            'message' => $request->message,
            'contact_id' => $request->contact_id,
          ]);


         $success['Replaycontacts']=New ReplaycontactResource($replaycontact );
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة رد بنجاح','Contact Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Replaycontact  $replaycontact
     * @return \Illuminate\Http\Response
     */
    public function show($replaycontact)
    {
        $replaycontact= Replaycontact::query()->find($replaycontact);
        if (is_null($replaycontact) || $replaycontact->is_deleted==1){
               return $this->sendError("الرد غير موجودة","contact is't exists");
               }
              $success['Replaycontacts']=New ReplaycontactResource($replaycontact);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الرد بنجاح','replaycontact showed successfully');
    }
    public function changeStatus($id)
    {
        $replaycontact = Replaycontact::query()->find($id);
        if (is_null($replaycontact) || $replaycontact->is_deleted==1){
         return $this->sendError(" الرد غير موجودة","replaycontact is't exists");
         }
        if($replaycontact->status === 'active'){
            $replaycontact->update(['status' => 'not_active']);
     }
    else{
        $replaycontact->update(['status' => 'active']);
    }
        $success['replaycontacts']=New ReplaycontactResource($replaycontact);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة الرد بنجاح',' replaycontact status updared successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Replaycontact  $replaycontact
     * @return \Illuminate\Http\Response
     */
    public function edit(Replaycontact $replaycontact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Replaycontact  $replaycontact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $replaycontact)
    {
        $replaycontact =Replaycontact::query()->find($replaycontact);
        if ( is_null($replaycontact) || $replaycontact->is_deleted==1){
            return $this->sendError("الرد غير موجودة"," replaycontact is't exists");
       }
            $input = $request->all();
           $validator =  Validator::make($input ,[
            'subject'=>'required|string|max:255',
            'message'=>'required|string',
            'contact_id'=>'required|exists:contacts,id'

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $replaycontact->update([
               'subject' => $request->input('subject'),
               'message' => $request->input('message'),
               'contact_id' => $request->input('contact_id'),

           ]);

           $success['replaycontacts']=New replaycontactResource($replaycontact);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','replaycontact updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Replaycontact  $replaycontact
     * @return \Illuminate\Http\Response
     */
    public function destroy($replaycontact)
    {
        $replaycontact =Replaycontact::query()->find($replaycontact);
        if (is_null($replaycontact) || $replaycontact->is_deleted==1){
            return $this->sendError(" الرد غير موجودة","replaycontact is't exists");
            }
           $replaycontact->update(['is_deleted' => 1]);

           $success['replaycontacts']=New replaycontactResource($replaycontact);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الرد بنجاح','replaycontact deleted successfully');
    }
}