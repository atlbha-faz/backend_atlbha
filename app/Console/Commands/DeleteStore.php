<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Store;
use App\Mail\SendMail;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use App\Events\VerificationEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verificationNotification;

class DeleteStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Store';

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
        
         $threeDaysAgo = Carbon::now()->subDays(3)->toDateString();
        $stores =\App\Models\Store::where('is_deleted', 0)->where('verification_status', 'pending')->whereDate('created_at', '<', $threeDaysAgo)->get();
        
        foreach($stores as $store){
            
            $users = User::where('store_id', $store->id)->get();
            foreach ($users as $user) {
               
                $comment = Comment::where('comment_for', 'store')->where('user_id', $user->id)->where('is_deleted', 0)->first();
                if ($comment != null) {
                    $comment->delete();
                }
                $user->delete();
            }
            $categorys = Category::where('is_deleted', 0)->where('store_id', $store->id)->get();
            foreach ($categorys as $category) {
                $category->delete();
            }
            $products = Product::where('store_id', $store->id)->get();
            foreach ($products as $product) {
                $product->delete();
            }
         
            $store->delete();
              
        }
        return 0;
    }
}
