<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'rateing' => 'required|numeric|lte:5',
            'store_id' => 'required_if:comment_for,store',
            'product_id' => 'required_if:comment_for,product',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $comment = Comment::create([
            'comment_text' => $request->comment_text,
            'rateing' => $request->rateing,
            'comment_for' => 'store',
            'product_id' => null,
            'store_id' => null,
            'user_id' => auth()->user()->id,

        ]);

        $success['comments'] = new CommentResource($comment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة تعليقك لمنصة اطلبها بنجاح', 'your  Atlobha comment Added successfully');

    }
}
