<?php

namespace App\Http\Controllers\api\homePages;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\atlobhaContactResource;
use App\Models\AtlobhaContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AtlobhaContactController extends BaseController
{
    public function index()
    {
        $success['atlobhaContact'] = atlobhaContactResource::collection(AtlobhaContact::where('is_deleted', 0)->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الرسائل  بنجاح', 'atlobhaContact return successfully');
    }
    public function store(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $atlobhaContact = AtlobhaContact::create([
            'name' =>  $request->input('name'),
            'email' => $request->input('email'),
            'title' => $request->input('title'),
            'content' =>  $request->input('content'),
        ]);
        $success['atlobhaContact'] = new atlobhaContactResource($atlobhaContact);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الرسالة  بنجاح', 'message Added successfully');
    }
    public function deleteAll(Request $request)
    {

        $atlobhaContacts = AtlobhaContact::whereIn('id', $request->id)
            ->where('is_deleted', 0)->get();
        if (count($atlobhaContacts) > 0) {
            foreach ($atlobhaContacts as $atlobhaContact) {

                $atlobhaContact->update(['is_deleted' => 1]);
                $success['atlobhaContacts'] = new atlobhaContactResource($atlobhaContact);

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف الرسائل بنجاح', 'atlobhaContact deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غيرموجودة', 'id is not exit');
        }

    }
}
