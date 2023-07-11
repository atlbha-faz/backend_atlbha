<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Store;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Models\Replaycomment;
use App\Http\Resources\CommentResource;
use App\Http\Resources\HomepageResource;
use App\Notifications\emailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\ReplaycommentResource;
use App\Http\Controllers\api\BaseController as BaseController;

class CommentController extends BaseController
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

        $success['comment_of_store']=CommentResource::collection(Comment::where('is_deleted',0)->where('comment_for','store')->where('store_id',auth()->user()->store_id)->get());
        $product_id=array();
        $products=Product::where('store_id',auth()->user()->store_id)->where('is_deleted',0)->get();
        foreach($products as $product ){
            $product_id[]= $product->id;
        }
        $success['comment_of_products']=CommentResource::collection(Comment::where('is_deleted',0)->where('comment_for','product')->whereIn('product_id',$product_id)->get());
        $success['commentActivation']=Homepage::where('is_deleted',0)->where('store_id',auth()->user()->store_id)->pluck('commentstatus')->first();

        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع التعليقات بنجاح','comments return successfully');
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
            'comment_text'=>'required|string|max:255',
            'rateing'=>'required|numeric|lt:5',
            'comment_for'=>'required|in:product,store',
            'store_id'=>'required_if:comment_for,store',
            'product_id'=>'required_if:comment_for,product',

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $comment = Comment::create([
            'comment_text' => $request->comment_text,
            'rateing' => $request->rateing,
            'comment_for' => $request->comment_for,
            'product_id' => $request->product_id,
            'store_id' => $request->store_id,
            'user_id' => auth()->user()->id,

          ]);


         $success['comments']=New CommentResource($comment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة تعليق بنجاح','comment Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($comment)
   {
       $comment = Comment::where('id',$comment)->where('store_id',auth()->user()->store_id)->first();
        if (is_null($comment) ||$comment->is_deleted==1){
        return $this->sendError("التعليق غير موجود","comment type is't exists");
        }


       $success['comments']=New CommentResource($comment);
       $success['status']= 200;

        return $this->sendResponse($success,'تم عرض التعليق بنجاح','comment showed successfully');
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
      {
         if (is_null($comment) ||$comment->is_deleted==1){
         return $this->sendError(" التعليق غير موجود","comment is't exists");
          }
         $input = $request->all();
         $validator =  Validator::make($input ,[
           'comment_text'=>'required|string|max:255',
            'rateing'=>'required|numeric',
            'comment_for'=>'required|in:product,store',
            'store_id'=>'required_if:comment_for,store',
            'product_id'=>'required_if:comment_for,product',
            // 'user_id'=>'required|exists:users,id'
         ]);
         if ($validator->fails())
         {
            # code...
            return $this->sendError(null,$validator->errors());
         }
         $comment->update([
            'comment_text' => $request->input('comment_text'),
            'rateing' => $request->input('rateing'),
            'product_id' => $request->input('product_id'),
            'store_id' => $request->input('store_id')
        //    'user_id' => $request->input('user_id'),
         ]);
         //$country->fill($request->post())->update();
            $success['comments']=New commentResource($comment);
            $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','comment updated successfully');
    }

    public function changeStatus($comment)
    {
       $comment = Comment::where('id',$comment)->first();
         if (is_null($comment) ||$comment->is_deleted==1){
         return $this->sendError("التعليق غير موجود","comment is't exists");
         }

        if($comment->status === 'active'){
        $comment->update(['status' => 'not_active']);
        }
        else{
        $comment->update(['status' => 'active']);
        }
        $success['comments']=New CommentResource($comment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة التعليق بنجاح','comment updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($comment)
      {
       $comment = Comment::where('id',$comment)->first();
         if (is_null($comment) ||$comment->is_deleted==1 ||$comment->store_id != auth()->user()->store_id){
         return $this->sendError("التعليق غير موجود","comment is't exists");
         }
        $comment->update(['is_deleted' => 1]);

        $success['comments']=New CommentResource($comment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم حذف التعليق بنجاح',' comment deleted successfully');
    }

    public function changeSatusall(Request $request)
    {

            $comments =Comment::whereIn('id',$request->id)->where('store_id',auth()->user()->store_id)->where('is_deleted',0)->get();
            if(count($comments)>0){
        foreach($comments as $comment)
        {
     
            if($comment->status === 'active'){
        $comment->update(['status' => 'not_active']);
        }
        else{
        $comment->update(['status' => 'active']);
        }
    
        $success['comments']= New CommentResource($comment);

            }
            $success['status']= 200;

        return $this->sendResponse($success,'تم تعديل حالة التعليق بنجاح','comment updated successfully');
        }
   else{
    $success['status']= 200;
 return $this->sendResponse($success,'المدخلات غيرموجودة','id is not exit');
  }
}
    public function replayComment(Request $request)
    {
       $input = $request->all();
        $validator =  Validator::make($input ,[
            // 'subject'=>'required|string|max:255',
            'comment_text'=>'required|string|max:255',
            'comment_id'=>'required|exists:comments,id',

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }


        $replay = Replaycomment::create([
            'comment_text' => $request->comment_text,
            'comment_id' => $request->comment_id,
            'user_id' => auth()->user()->id,
        ]);
        $store=Store::where('id', auth()->user()->store_id)->value('store_name');
        $data= [
            'subject' =>"رد على التعليق",
            'message' => $request->comment_text,
            'store_id' =>    $store,
        ];
        $replaycomment =Comment::where('id',$request->comment_id)->where('is_deleted',0)->first();
          if($replaycomment->user != null)
          {
            Notification::send($replaycomment->user , new emailNotification($data));
          }
         $success['replays']=New ReplaycommentResource($replay);
        $success['status']= 200;

         return $this->sendResponse($success,'تم اضافة رد بنجاح',' Added successfully');
    }
    public function commentActivation()
{

     $commentActivation = Homepage::where('store_id',auth()->user()->store_id)->first();
        if (is_null($commentActivation) || $commentActivation->is_deleted==1 ){
         return $this->sendError("قسم التعليقات غير موجودة","comment's section is't exists");
         }
        if($commentActivation->	commentstatus === 'active'){
            $commentActivation->update(['commentstatus' => 'not_active']);

     }
    else{

        $commentActivation->update(['commentstatus' => 'active']);
    }
        $success['commentActivation']=Homepage::where('is_deleted',0)->where('store_id',auth()->user()->store_id)->pluck('commentstatus')->first();
       $success['status']= 200;

        return $this->sendResponse($success,'تم التعديل بنجاح',' updated successfully');
}

}
