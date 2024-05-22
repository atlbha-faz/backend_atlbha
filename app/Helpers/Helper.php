<?php
namespace App\Helpers;

use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Option;
use App\Models\Product;
use App\Models\Importproduct;

class Helper{
  public static function sendError($error ,$error_en , $errorMessages=[], $code=200)
  {
   $response = [
       'success' =>false ,
       'message'=>['en' => $error_en, 'ar' => $error]

   ];

   if (!empty($errorMessages)) {
       # code...
       $response['data']= $errorMessages;
   }else{
       $response['data']= null;
   }
       return response()->json($response,$code);

  }
  public static function orderProductShow($id){
    
    $order = Order::where('id', $id)->whereHas('items', function ($q) {
        $q->where('store_id', null);
    })->first();
    
    $storeAdmain = User::whereIn('user_type', ['store', 'store_employee'])->where('id', $order->user_id)->first();

    if ($storeAdmain != null) {
        $storeid = Store::where('id', $storeAdmain->store_id)->first();

    }
    foreach ($order->items as $orderItem) {
        $product = Product::where('id', $orderItem->product_id)->where('store_id', null)->first();
        $import_product_existing = Importproduct::where('product_id', $product->id)->where('store_id', $storeid->id)->first();

        if ($import_product_existing == null) {
            $import_product = Importproduct::create([
                'product_id' => $product->id,
                'store_id' => $storeid->id,
                'price' => $orderItem->price,
                'qty' => $orderItem->quantity,
            ]);
            $new_stock = $product->stock - $import_product->qty;
            $product->update([
                'stock' => $new_stock,
            ]);

            if ($orderItem->option_id != null) {
                $option = Option::where('is_deleted', 0)->where('original_id', $orderItem->option_id)->where('importproduct_id', $import_product->id)->first();
                if ($option == null) {
                    $orginal_option = Option::where('is_deleted', 0)->where('id', $orderItem->option_id)->where('importproduct_id', null)->where('original_id', null)->first();
                    $newOption = $orginal_option->replicate();
                    $newOption->product_id = null;
                    $newOption->original_id = $orginal_option->id;
                    $newOption->importproduct_id = $import_product->id;
                    $newOption->quantity = $orderItem->quantity;
                    $newOption->price = $orderItem->price;
                    $newOption->save();
                } else {
                    $qty = $option->quantity;
                    $option->update([
                        'quantity' => $qty + $orderItem->quantity,
                    ]);

                }
            }
        } else {
            $qty_product = $import_product_existing->qty;
            $import_product_existing->update([
                'qty' => $qty_product + $orderItem->quantity,
            ]);
            if ($orderItem->option_id != null) {

                $option = Option::where('is_deleted', 0)->where('original_id', $orderItem->option_id)->where('importproduct_id', $import_product_existing->id)->first();
                if ($option == null) {
                    $orginal_option = Option::where('is_deleted', 0)->where('id', $orderItem->option_id)->where('original_id', null)->first();

                    $newOption = $orginal_option->replicate();
                    $newOption->product_id = null;
                    $newOption->original_id = $orginal_option->id;
                    $newOption->importproduct_id = $import_product_existing->id;
                    $newOption->quantity = $orderItem->quantity;
                    $newOption->price = $orderItem->price;
                    $newOption->save();
                } else {
                    $qty = $option->quantity;
                    $option->update([
                        'quantity' => $qty + $orderItem->quantity,
                    ]);

                }
            }
        }

    }
  }

}