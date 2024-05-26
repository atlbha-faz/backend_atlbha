<?php
namespace App\Helpers;


use Carbon\Carbon;
use App\Models\Store;
use App\Models\Package_store;
use App\Http\Resources\MaintenanceResource;

class StoreHelper
{
    public static function check_store_existing($id)
    {
        $store = Store::where('domain', $id)->where('verification_status', 'accept')->whereNot('package_id', null)->whereDate('end_at', '>', Carbon::now())->first();

        if (!is_null($store)) {
            $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        }
        if (is_null($store) || $store->is_deleted != 0 || is_null($store_package) || $store_package->status == "not_active") {
            return $this->sendError("المتجر غير موجود", "Store is't exists");
        }
        if ($store->maintenance != null) {
            if ($store->maintenance->status == 'active') {
                $success['maintenanceMode'] = new MaintenanceResource($store->maintenance);

                $success['status'] = 200;

                return $this->sendResponse($success, 'تم ارجاع وضع الصيانة بنجاح', 'Maintenance return successfully');
            }
        }
        if ($store != null) {
            return $store;
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'Store is not exists');
        }

    }

}
