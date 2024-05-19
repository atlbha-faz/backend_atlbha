<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ArchiveIdleOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $thirtyMinutesAgo =  Carbon::now()->subMinutes(30)->format('Y-m-d H:i:s');
        $orders =\App\Models\Order::whereNot('paymentype_id',4)->where('is_archive',0)->whereNot('payment_status','paid')->whereDate('created_at', '<',$thirtyMinutesAgo)->get();
        foreach($orders  as $order){
            $orders_items =\App\Models\OrderItem::where('order_id',$order->id)->get();
            foreach($orders_items  as $orders_item){
                $product=\App\Models\Product::where('id',$orders_item->product_id )->where('store_id',$orders_item->store_id)->first();
                if( $product){
                    $product->stock=$product->stock+$orders_item->quantity;
                    $product->save();
                }
                else{
                    $import_product=\App\Models\Importproduct::where('product_id',$orders_item->product_id )->where('store_id',$orders_item->store_id)->first();
                    if( $import_product){
                        $import_product->qty=$import_product->qty+$orders_item->quantity;
                        $import_product->save();
                    }

                }
            }
            $order->is_archive=1;
            $order->save();
        }
        
    }
}
