<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\ContactResource;
use App\Mail\SendMail;
use App\Models\Contact;
use App\Models\Replaycontact;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {

        $success['emails'] = ContactResource::collection(Contact::where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع جميع االرسائل بنجاح', 'email return successfully');
    }

    public function show($id)
    {
        $contact = Contact::query()->find($id);

        $success['contact'] = new ContactResource($contact);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع  الرسالة بنجاح', 'email  return successfully');
    }
    public function deleteEmail($id)
    {
        $contact = Contact::query()->find($id);
        if (is_null($contact)) {
            return $this->sendError("الرسالة غير موجود", "contact is't exists");
        }
        $contact->update(['is_deleted' => $contact->id]);

        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الرسالة بنجاح', 'contact  deleted successfully');
    }

    public function deleteEmailAll(Request $request)
    {

        $contacts = Contact::whereIn('id', $request->id)->get();
        foreach ($contacts as $contact) {
            $contact->update(['is_deleted' => $contact->id]);
        }
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم حذف الرسالة بنجاح', 'Contact deleted successfully');
    }

    public function addEmail(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:255',
            'store_id' => 'exists:stores,id',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        $data = [
            'subject' => $request->subject,
            'message' => $request->message,
            'contact_id' => $request->store_id,
        ];
        $data1 = [
            'subject' => $request->subject,
            'message' => $request->message,
            'store_id' => $request->store_id,
        ];
        $contact = Replaycontact::create($data);
        $users = User::where('store_id', $request->store_id)->where('user_type', 'store')->get();

        foreach ($users as $user) {

            try {
                Mail::to($user->email)->send(new SendMail($data1));
            } catch (Exception $e) {
                return $this->sendError('صيغة البريد الالكتروني غير صحيحة', 'The email format is incorrect.');
            }
        }
        $success['contacts'] = new ContactResource($contact);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة بنجاح', ' Added successfully');
    }

}
