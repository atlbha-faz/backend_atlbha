<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PermanentDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deletion:permanent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'permanent deletion';

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
       
            $monthAgo = Carbon::now()->subDays(30)->toDateString();
            $stores = \App\Models\Store::where('is_deleted', '!=', 0)->where('verification_status', 'pending')->whereDate('updated_at','<',$monthAgo)->get();
            foreach ($stores as $store) {

                $users = User::where('store_id', $store->id)->get();
                foreach ($users as $user) {

                    $comments = Comment::where('comment_for', 'store')->where('user_id', $user->id)->where('is_deleted', 0)->get();
                    if ($comments != null) {
                        foreach ($comments as $comment) {
                            $comment->delete();
                        }
                    }
                    $user->delete();
                }
                $categorys = Category::where('is_deleted', 0)->where('store_id', $store->id)->get();
                if ($categorys != null) {
                    foreach ($categorys as $category) {
                        $category->delete();
                    }
                }
                $products = Product::where('store_id', $store->id)->get();
                if ($products != null) {
                    foreach ($products as $product) {
                        $product->delete();
                    }
                }
                $store->delete();
            }
        
        return 0;
    }
}
