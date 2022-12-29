<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;

class StockController extends BaseController
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
         $success['total_stock']=Product::where('is_deleted',0)->where('for','etlobha')->count();
         $success['finished_products']=Product::where('is_deleted',0)->where('for','etlobha')->where('stock','0')->count();
         $success['finished_soon']=Product::where('is_deleted',0)->where('for','etlobha')->where('stock', '<','20')->count();
         $date = Carbon::now()->subDays(7);
        $success['last_week_product_added']=Product::where('is_deleted',0)->where('for','etlobha')->where('created_at', '>=', $date)->count();

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function report(){
         $success['total_stock']=Product::where('is_deleted',0)->where('for','etlobha')->count();
         $success['finished_products']=Product::where('is_deleted',0)->where('stock','0')->count();
         $success['finished_soon']=Product::where('is_deleted',0)->where('stock', '<','20')->count();




    }
}
