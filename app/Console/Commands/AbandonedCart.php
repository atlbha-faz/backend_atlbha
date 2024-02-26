<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Cart;
use App\Models\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Events\VerificationEvent;
use App\Notifications\verificationNotification;

class AbandonedCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:abandoned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Abandoned Cart';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $carts =\App\Models\Cart::whereNot('count',0)->whereDate('updated_at', '<=', Carbon::now()->subHours(24)->format('Y-m-d'))->get();
        
        foreach($carts as $cart){
            if($cart->discount_expire_date < Carbon::now()->toDateString()){
                   $cart->total=$cart->total +$cart->discount_total;
                    if($cart->free_shipping === 1){
                       $cart->total =$cart->total +$cart->shipping_price;
                    }
                   $cart->discount_total=0;
                    $cart->discount_value=0;
                    $cart->free_shipping=0;
                      $cart->message=null;
                    $cart->	shipping_price=35;
                      $cart->discount_expire_date=null;
                     $cart->timestamps = false;
                   $cart->save();
                   $cart->tax= $cart->total * 0.15;
                   $cart->subtotal= $cart->total -  $cart->tax;
                   $cart->timestamps = false;
                   $cart->save();
                
            }
     
              
        }
        return 0;
    }
}
