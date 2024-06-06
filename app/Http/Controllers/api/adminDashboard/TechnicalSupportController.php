<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\TechnicalsupportResource;
use App\Models\TechnicalSupport;
use DB;
use Illuminate\Http\Request;
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
        $success['Store_Technicalsupports'] = count(DB::table('technical_supports')->where('is_deleted', 0)
                ->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw("store_id"))
                ->get());
        if (TechnicalSupport::where('is_deleted', 0)->count() > 0) {
            $success['percent_of_Store_Technicalsupports'] = (count(DB::table('technical_supports')
                    ->select(DB::raw('count(*) as total'))
                    ->groupBy(DB::raw("store_id"))
                    ->get()) / (TechnicalSupport::where('is_deleted', 0)->count()) * 100) . "%";
        } else {
            $success['percent_of_Store_Technicalsupports'] = "0%";
        }
        $success['TechnicalsupportsCount'] = TechnicalSupport::where('is_deleted', 0)->count();
        $success['pending_Technicalsupports'] = TechnicalSupport::where('is_deleted', 0)->where('supportstatus', 'pending')->count();
        $success['finished_Technicalsupports'] = TechnicalSupport::where('is_deleted', 0)->where('supportstatus', 'finished')->count();
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data=TechnicalSupport::where('is_deleted', 0)->orderByDesc('created_at');
        $data= $data->paginate($count);
        $success['Technicalsupports'] = TechnicalsupportResource::collection($data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
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
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function show($technicalSupport)
    {
        $technicalSupport = TechnicalSupport::query()->find($technicalSupport);
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
    public function update(Request $request, TechnicalSupport $technicalSupport)
    {

    }

    public function changeStatus($id, Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'supportstatus' => 'required|in:finished,not_finished,pending',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $technicalSupport = TechnicalSupport::query()->find($id);
        if (is_null($technicalSupport) || $technicalSupport->is_deleted != 0) {
            return $this->sendError("طلب الدعم غير موجود", "technical Support is't exists");
        }

        $technicalSupport->update(['supportstatus' => $request->supportstatus]);

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
        $technicalSupport = TechnicalSupport::query()->find($technicalSupport);
        if (is_null($technicalSupport) || $technicalSupport->is_deleted != 0) {
            return $this->sendError("الوحدة غير موجودة", "technicalSupport is't exists");
        }
        $technicalSupport->update(['is_deleted' => $technicalSupport->id]);

        $success['technicalSupport'] = new TechnicalSupportResource($technicalSupport);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف طلب الدعم بنجاح', 'technical Support deleted successfully');
    }
    public function changeStatusAll(Request $request)
    {
        $technicalSupports = TechnicalSupport::whereIn('id', $request->id)->get();
        foreach ($technicalSupports as $technicalSupport) {
            if (is_null($technicalSupport) || $technicalSupport->is_deleted != 0) {
                return $this->sendError("الشكوى غير موجود", "technicalSupport is't exists");
            }
        }
        foreach ($technicalSupports as $technicalSupport) {
            if ($technicalSupport->status === 'active') {
                $technicalSupport->update(['status' => 'not_active']);
            } else {
                $technicalSupport->update(['status' => 'active']);
            }
            $success['technicalSupports'] = new TechnicalSupportResource($technicalSupport);
        }

        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعدبل حالة الشكوى بنجاح', ' technicalSupport status updared successfully');

    }
    public function searchTechnicalSupport(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $query = $request->input('query');
        $supports = TechnicalSupport::where('is_deleted', 0)
        ->where('title', 'like', "%$query%")->orderByDesc('created_at');
        $supports=$supports->paginate($count);

        $success['query'] = $query;
        $success['total_result'] = $supports->total();
        $success['page_count'] = $supports->lastPage();
        $success['current_page'] = $supports->currentPage();
        $success['supports'] = TechnicalsupportResource::collection($supports);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الدعم الفني بنجاح', 'supports Information returned successfully');

    }
}
