<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class EtlobhaController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function etlobhaComment(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'comment_text' => 'required|string|max:255',
            'rateing' => 'nullable',
            // 'store_id' => 'required_if:comment_for,store',
            // 'product_id' => 'required_if:comment_for,product',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
       $users= User::whereIn('user_type', ['store','store_employee'])->where('store_id', auth()->user()->store_id)->where('is_deleted',0)->pluck('id')->toArray();
        $exitComment=Comment::where('comment_for','store')->whereIn('user_id',$users)->where('is_deleted',0)->first();
       if($exitComment != null){
        return $this->sendError('تم التعليق مسبقا', 'comment exists');
           }
        $comment = Comment::create([
            'comment_text' => $request->comment_text,
            'rateing' => $request->rateing,
            'comment_for' => 'store',
            'product_id' => null,
            'store_id' => null,
            'user_id' => auth()->user()->id,
            'status' => 'not_active',
        ]);

        $success['comments'] = new CommentResource($comment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة تعليقك لمنصة اطلبها بنجاح', 'your  Atlobha comment Added successfully');

    }
    public function existComment()
    {
        $users= User::whereIn('user_type', ['store','store_employee'])->where('store_id', auth()->user()->store_id)->where('is_deleted',0)->pluck('id')->toArray();
        $exitComment=Comment::where('comment_for','store')->whereIn('user_id',$users)->where('is_deleted',0)->first();
       if($exitComment != null){
        $success['existComment'] = true;
           }
           else{
           $success['existComment'] = false;
           }
           $success['status'] = 200;

           return $this->sendResponse($success, 'تم إرجاع حالة التعليق بنجاح', 'your comment return successfully');
   
    }

}
