<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Package_store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'monthly_price', 'yearly_price', 'discount', 'status', 'is_deleted'];

    public function stores()
    {
        return $this->belongsToMany(
            Store::class,
            'packages_stores',
            'package_id',
            'store_id'
        );
    }

    public function plans()
    {
        return $this->belongsToMany(
            Plan::class,
            'packages_plans',
            'package_id',
            'plan_id'
        );
    }
    public function templates()
    {
        return $this->belongsToMany(
            Template::class,
            'packages_templates',
            'package_id',
            'template_id'
        );
    }
    public function left($id)
    {
        $store = Store::where('id', $id)->first();
        $store_package = Package_store::where('package_id', $store->package_id)->where('store_id', $store->id)->where('payment_status','paid')->orderBy('id', 'DESC')->first();

        if ($store->package_id == null || $store->periodtype == "6months" || $store_package == null) {
            return 0;
        } else {
            $day = Store::select('end_at')->where('id', $id)->first();
            $date1 = new DateTime($day->end_at);
            $now_date = Carbon::now();
            $interval = $date1->diff($now_date);
            return $interval->days;
        }
    }

}
