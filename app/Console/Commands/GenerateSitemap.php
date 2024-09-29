<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SitemapController;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap for posts and products';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sitemapController = new SitemapController();

        // Generate sitemap for posts
        $postsSitemap = $sitemapController->posts();

        // Save the sitemap for posts to a file
        file_put_contents(public_path('../../sitemap-posts.xml'), $postsSitemap);

        // Generate sitemap for products
        $productsSitemap = $sitemapController->products();

        // Save the sitemap for products to a file
        file_put_contents(public_path('../../sitemap-products.xml'), $productsSitemap);

        $this->info('Sitemap generated successfully.');
    }
}
