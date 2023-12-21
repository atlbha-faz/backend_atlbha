<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\importsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Importproduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use DB;

class IndexController extends BaseController
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
        $success['visits'] = 10;
        $success['orders_count'] = Order::whereHas('items', function ($q) {
            $q->where('store_id', auth()->user()->store_id);
        })->count();
        $success['sales'] = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->sum('total_price');
        $imports_id = Importproduct::where('store_id', auth()->user()->store_id)->count();
        $products = ProductResource::collection(Product::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());

        $import = Product::join('importproducts', 'products.id', '=', 'importproducts.product_id')->where('products.is_deleted', 0)->where('importproducts.store_id', auth()->user()->store_id)
            ->get(['products.*', 'importproducts.price', 'importproducts.status'])->makeHidden(['products.*status', 'selling_price', 'store_id']);
        $imports = importsResource::collection($import);

        $all = $products->merge($imports);

        $success['products_count'] = $all->count();

        $success['orders'] = OrderResource::collection(Order::where('store_id', auth()->user()->store_id)->orderBy('created_at', 'DESC')->take(7)->get());

        $product_id = array();
        $products = DB::table('order_items')->join('products', 'order_items.product_id', '=', 'products.id')->where('products.store_id', auth()->user()->store_id)
            ->select('products.id', DB::raw('sum(order_items.total_price) as sales'), DB::raw('sum(order_items.quantity) as count'))
            ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();
        foreach ($products as $product) {
            $product_id[] = $product->id;
        }
        $success['products'] = ProductResource::collection(Product::whereIn('id', $product_id)->where('is_deleted', 0)->take(7)->get());
        $array_sales_monthly = array();
        $array_sales_weekly = array();
        $array_sales_daily = array();

        for ($i = 1; $i <= 12; $i++) {

            $result = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
            $array_sales_monthly[date('M', mktime(0, 0, 0, $i, 10))] = $result !== null ? $result : 0;
        }
        $start_date = date('Y-m-d', strtotime('last saturday', strtotime(date('Y-m-d'))));
        $end_date = date('Y-m-d', strtotime('next friday', strtotime(date('Y-m-d'))));
        if (date('l') == "Saturday") {
            $start_date = date('Y-m-d');
        }
        if (date('l') == "Friday") {
            $end_date = date('Y-m-d');
        }
        for ($i = 1; $i <= 7; $i++) {
            $result = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
            $array_sales_weekly["" . $i . " الاسبوع"] = $result !== null ? $result : 0;
            $start_date = date('Y-m-d', strtotime('-7 days', strtotime($start_date)));
            $end_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        }
        for ($i = 1; $i <= 7; $i++) {
            $x = $i - 1;
            $result = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->whereDate('created_at', date('Y-m-d', strtotime("-" . $x . " days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
            $array_sales_daily[(date('D', strtotime("-" . $x . " days")))] = $result !== null ? $result : 0;
        }

        $success['array_sales_monthly'] = $array_sales_monthly;
        $success['array_sales_weekly'] = $array_sales_weekly;
        $success['array_sales_daily'] = $array_sales_daily;

        $sales_monthly = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d', strtotime("-30 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
        $success['sales_monthly'] = $sales_monthly !== null ? $sales_monthly : 0;
        $success['sales_monthly_compare'] = 0;
        $sales_monthly_prev = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d', strtotime("-60 days")))->whereDate('created_at', '<=', date('Y-m-d', strtotime("-30 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
        if ($success['sales_monthly'] > $sales_monthly_prev) {
            $success['sales_monthly_compare'] = 1;
        }
        $sales_weekly = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d', strtotime("-7 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
        $success['sales_weekly'] = $sales_weekly !== null ? $sales_weekly : 0;

        $success['sales_weekly_compare'] = 0;
        $sales_weekly_prev = DB::table('orders')->where('order_status', 'completed')->where('store_id', auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d', strtotime("-14 days")))->whereDate('created_at', '<=', date('Y-m-d', strtotime("-7 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
        if ($success['sales_weekly'] > $sales_weekly_prev) {
            $success['sales_weekly_compare'] = 1;
        }
        if ($success['sales_monthly'] != null) {
            $success['sales_percent'] = (int) number_format($success['sales_weekly'] / $success['sales_monthly'] * 100, 0, '.', '');
            $success['sales_avg'] = (double) number_format($success['sales_weekly'] / $success['sales_monthly'], 2, '.', '');

        } else {
            $success['sales_percent'] = 0;
            $success['sales_avg'] = 0;

        }

        $sales_avg_prev = $sales_weekly_prev / 50;
        $success['sales_avg_compare'] = 0;
        if ($success['sales_avg'] > $sales_avg_prev) {
            $success['sales_avg_compare'] = 1;
        }
        $success['registration_marketer'] = Setting::orderBy('id', 'desc')->pluck('registration_marketer')->first();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع بنجاح', 'return successfully');
    }
}
