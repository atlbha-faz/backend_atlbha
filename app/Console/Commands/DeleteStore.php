<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $setting = Setting::orderBy('id', 'desc')->first();
        if ($setting->registration_status == "registration_without_admin") {
            $threeDaysAgo = Carbon::now()->subDays(7)->toDateString();
            $stores = \App\Models\Store::where('is_deleted', 0)->where('verification_status', 'pending')->whereDate('created_at', '<', $threeDaysAgo)->get();

            foreach ($stores as $store) {

                $users = User::where('store_id', $store->id)->get();
                foreach ($users as $user) {

                    $comments = Comment::where('comment_for', 'store')->where('user_id', $user->id)->where('is_deleted', 0)->get();
                    if ($comments != null) {
                        foreach ($comments as $comment) {
                            $comment->update(['is_deleted' => $comment->id]);
                        }
                    }
                    $user->update(['is_deleted' => $user->id]);
                }
                $categorys = Category::where('is_deleted', 0)->where('store_id', $store->id)->get();
                if ($categorys != null) {
                    foreach ($categorys as $category) {
                        $category->update(['is_deleted' => $category->id]);
                    }
                }
                $products = Product::where('store_id', $store->id)->get();
                if ($products != null) {
                    foreach ($products as $product) {
                        $product->update(['is_deleted' => $product->id]);
                    }
                }
                $store->update(['is_deleted' => $store->id]);

            }
        }
        return 0;
    }
}
