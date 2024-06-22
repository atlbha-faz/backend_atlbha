<?php

namespace App\Http\Controllers\api\adminDashboard;

use Illuminate\Http\Request;
use App\Models\Replaycomment;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ReplaycommentRequest;
use App\Http\Resources\ReplaycommentResource;
use App\Http\Controllers\api\BaseController as BaseController;

class ReplaycommentController extends BaseController
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

        $success['replaycomment'] = ReplaycommentResource::collection(Replaycomment::where('is_deleted', 0)->orderByDesc('created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع ردود التعليقات بنجاح', 'replay comment return successfully');
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
    public function store(ReplaycommentRequest $request)
    {

        $replaycomment = Replaycomment::create([
            'comment_text' => $request->comment_text,
            'comment_id' => $request->comment_id,
            'user_id' => $request->user_id,

        ]);

        $success['replaycomments'] = new ReplaycommentResource($replaycomment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة رد تعليق بنجاح', 'replay comment Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function show($replaycomment)
    {
        $replaycomment = Replaycomment::query()->find($replaycomment);
        if (is_null($replaycomment) || $replaycomment->is_deleted != 0) {
            return $this->sendError("'رد التعليق غير موجودة", "replay comment type is't exists");
        }

        $success['replaycomments'] = new ReplaycommentResource($replaycomment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض رد التعليق بنجاح', 'replay comment showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function edit(Replaycomment $replaycomment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function update(ReplaycommentRequest $request, $replaycomment)
    {
        $replaycomment = Replaycomment::query()->find($replaycomment);
        if (is_null($replaycomment) || $replaycomment->is_deleted != 0) {
            return $this->sendError(" التعليق غير موجود", "replay comment is't exists");
        }
        $replaycomment->update([
            'comment_text' => $request->input('comment_text'),
            'comment_id' => $request->input('comment_id'),
            'user_id' => $request->input('user_id'),
        ]);

        $success['replaycomments'] = new ReplaycommentResource($replaycomment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'replay comment updated successfully');
    }

    public function changeStatus($id)
    {
        $replaycomment = Replaycomment::query()->find($id);
        if (is_null($replaycomment) || $replaycomment->is_deleted != 0) {
            return $this->sendError("التعليق غير موجود", "replaycomment is't exists");
        }

        if ($replaycomment->status === 'active') {
            $replaycomment->update(['status' => 'not_active']);
        } else {
            $replaycomment->update(['status' => 'active']);
        }
        $success['replaycomments'] = new ReplaycommentResource($replaycomment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة رد التعليق بنجاح', 'replay comment updated successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Replaycomment  $replaycomment
     * @return \Illuminate\Http\Response
     */
    public function destroy($replaycomment)
    {
        $replaycomment = Replaycomment::query()->find($replaycomment);
        if (is_null($replaycomment) || $replaycomment->is_deleted != 0) {
            return $this->sendError("التعليق غير موجود", "replaycomment is't exists");
        }
        $replaycomment->update(['is_deleted' => $replaycomment->id]);

        $success['replaycomments'] = new ReplaycommentResource($replaycomment);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف  رد التعليق بنجاح', ' replay comment deleted successfully');
    }
}
