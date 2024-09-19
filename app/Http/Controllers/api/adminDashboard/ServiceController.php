<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Store;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ServiceAdminRequest;
use App\Http\Controllers\api\BaseController as BaseController;

class ServiceController extends BaseController
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
        $data = Service::where('is_deleted', 0)->whereNotIn('id', [75, 76])->orderByDesc('created_at');
        $data = $data->paginate($count);
        $success['Services'] = ServiceResource::collection($data);
        $success['page_count'] = $data->lastPage();
        $success['current_page'] = $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الخدمات بنجاح', 'Services return successfully');
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
    public function store(ServiceAdminRequest $request)
    {
       
        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'file' => $request->file,
            'price' => $request->price,
        ]);

        $success['services'] = new ServiceResource($service);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الخدمة بنجاح', ' service Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceAdminRequest $request, Service $service)
    {
        if (is_null($service) || $service->is_deleted != 0) {
            return $this->sendError("الخدمة غير موجودة", "service is't exists");
        }

        $service->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'file' => $request->file,
            'price' => $request->input('price'),
            'status' => 'active',
        ]);

        $success['services'] = new ServiceResource($service);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'service updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */

    public function deleteAll(Request $request)
    {

        $services = Service::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($services) > 0) {
            foreach ($services as $service) {

                $service->update(['is_deleted' => $service->id]);
                $success['services'] = new ServiceResource($service);

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف الخدمة بنجاح', 'service deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }
    public function showDetail($service)
    {
        $service = Service::where('id', $service)->first();
        if (is_null($service) || $service->is_deleted != 0) {
            return $this->sendError("الخدمة غير موجودة", "service is't exists");
        }
        $orders = $service->websiteorders->where('payment_status','paid');
        $store_id = [];
        if (!is_null($orders)) {
            foreach ($orders as $order) {
                if($order->store_id != null){
                $store_id[] = $order->store_id;
                }
            }
        }

        $stores = Store::whereIn('id', $store_id)->where('is_deleted', 0)->get();
        $success['stores'] = StoreResource::collection($stores);
        $success['service'] = new ServiceResource($service);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الخدمة  بنجاح', 'service showed successfully');
    }

    public function changeStatus($id)
    {
        $service = Service::query()->find($id);
        if (is_null($service) || $service->is_deleted != 0) {
            return $this->sendError("  الخدمة غير موجودة", "service is't exists");
        }

        if ($service->status === 'active') {
            $service->update(['status' => 'not_active']);
        } else {
            $service->update(['status' => 'active']);
        }
        $success['services'] = new ServiceResource($service);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة الخدمة بنجاح', 'service updated successfully');

    }
    public function searchServiceName(Request $request)
    {
        $query = $request->input('query');
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;

        $services = Service::where('is_deleted', 0)->where('name', 'like', "%$query%")->orderBy('created_at', 'desc')->paginate($count);
        $success['query'] = $query;
        $success['total_result'] = $services->total();
        $success['page_count'] = $services->lastPage();
        $success['current_page'] = $services->currentPage();
        $success['services'] = ServiceResource::collection($services);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الخدمات  بنجاح', 'service Information returned successfully');

    }
}
