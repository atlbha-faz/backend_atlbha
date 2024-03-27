<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\TechnicalsupportResource;
use App\Mail\SendMail2;
use App\Models\Store;
use App\Models\TechnicalSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TechnicalSupportController extends BaseController
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
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $technical_supports = TechnicalsupportResource::collection(TechnicalSupport::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->orderByDesc('created_at')->paginate($count));
        $success['page_count'] = $technical_supports->lastPage();
        $success['current_page'] =$technical_supports->currentPage();
        $success['Technicalsupports'] =$technical_supports;
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الدعم الفني بنجاح', 'Technical Support return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function show($technicalSupport)
    {
        $technicalSupport = TechnicalSupport::where('id', $technicalSupport)->where('store_id', auth()->user()->store_id)->first();
        if (is_null($technicalSupport) || $technicalSupport->is_deleted != 0) {
            return $this->sendError("طلب الدعم الفني غير موجود", "Technical Support is't exists");
        }

        $success['technicalSupports'] = new TechnicalSupportResource($technicalSupport);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض بنجاح', 'Technical Support showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function edit(TechnicalSupport $technicalSupport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */

    public function changeStatus($id)
    {
        $technicalSupport = TechnicalSupport::query()->find($id);
        if (is_null($technicalSupport) || $technicalSupport->is_deleted != 0) {
            return $this->sendError("طلب الدعم غير موجود", "technical Support is't exists");
        }

        if ($technicalSupport->supportstatus === 'not_finished') {
            $technicalSupport->update(['supportstatus' => 'finished']);
        } else {
            $technicalSupport->update(['supportstatus' => 'not_finished']);
        }
        $success['technicalSupports'] = new TechnicalSupportResource($technicalSupport);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة طلب الدعم بنجاح', 'technical Support updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function destroy($technicalSupport)
    {
        $technicalSupport = TechnicalSupport::where('id', $technicalSupport)->where('store_id', auth()->user()->store_id)->first();

        if (is_null($technicalSupport) || $technicalSupport->is_deleted != 0) {
            return $this->sendError("الصف غير موجودة", "technicalSupport is't exists");
        }
        $technicalSupport->update(['is_deleted' => $technicalSupport->id]);

        $success['technicalSupport'] = new TechnicalSupportResource($technicalSupport);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف طلب الدعم بنجاح', 'technical Support deleted successfully');
    }

    public function deleteall(Request $request)
    {

        $technicalSupports = technicalSupport::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get();
        // dd($technicalSupports);
        if (count($technicalSupports) > 0) {
            foreach ($technicalSupports as $technicalSupport) {

                $technicalSupport->update(['is_deleted' => $technicalSupport->id]);
                $success['technicalSupports'] = new TechnicalSupportResource($technicalSupport);

            }

            $success['status'] = 200;

            return $this->sendResponse($success, 'تم حذف الدعم الفني بنجاح', 'technicalSupport deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendError("الصف غير موجودة", "technicalSupport is't exists");
        }

    }
    public function replay(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            // 'subject'=>'required|string|max:255',
            'replay_text' => 'required|string|max:255',
            'technical_support_id' => 'required|exists:technical_supports,id',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }

        $store = Store::where('id', auth()->user()->store_id)->value('store_name');
        //dd(Store::where('id', auth()->user()->store_id)->get());
        $data = [
            'subject' => "رد على رسالة تواصل معنا",
            'message' => $request->replay_text,
            'store_id' => $store,
            'store_email' => Store::where('id', auth()->user()->store_id)->first()->store_email,
        ];
        $replaytechnicalsupport = technicalSupport::where('id', $request->technical_support_id)->where('is_deleted', 0)->first();
        if ($replaytechnicalsupport->user != null) {
            // Notification::send($replaycomment->user , new emailNotification($data));
            try {
                Mail::to($replaytechnicalsupport->user->email)->send(new SendMail2($data));
            } catch (Exception $e) {
                return $this->sendError('صيغة البريد الالكتروني غير صحيحة', 'The email format is incorrect.');
            }
        }
        // $success['replays']=New ReplaycommentResource($replay);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم اضافة رد بنجاح', ' Added successfully');
    }
    public function searchTechnicalSupport(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $technical_supports = technicalSupport::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)
            ->where('title', 'like', "%$query%")->orderBy('created_at', 'desc')
            ->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $technical_supports->total();
        $success['page_count'] = $technical_supports->lastPage();
        $success['current_page'] = $technical_supports->currentPage();
        $success['technical_supports'] = TechnicalSupportResource::collection($technical_supports);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الدعم الفني بنجاح', 'technical_supports Information returned successfully');

    }
}
