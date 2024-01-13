<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Events\VerificationEvent;
use App\Notifications\verificationNotification;

class AcceptVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:accept';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accept Verification';

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
        $stores =\App\Models\Store::where('verification_status','admin_waiting')->get();
        
        foreach($stores as $store){
            
        $date = Carbon::now()->toDateTimeString();

        $store->verification_status= 'accept';
        $store->verification_date =$date;
        $store->save();
        
        $user = \App\Models\User::where('store_id', $store->id)->where('user_type','store')->first();
        $data = [
            'message' => ' تم قبول توثيق المتجر',
            'store_id' => $store->id,
            'user_id' => auth()->user()->id,
            'type' => "store_request",
            'object_id' => $store->id,
        ];

            Notification::send($user, new verificationNotification($data));
            Mail::to($user->email)->send(new SendMail($data));

        
        event(new VerificationEvent($data));
     
              
        }
        return 0;
    }
}
