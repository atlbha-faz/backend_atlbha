<?php

namespace App\Http\Controllers;

use App\Models\Importproduct;
use App\Models\Page;
use App\Models\Product;

class SitemapController extends Controller
{
    public function posts()
    {
        $posts = Page::where('status', 'active')->where('is_deleted', 0)->get();
        $sitemap ='<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($posts as $post) {
            if ($post->store_id == null) {
                if ($post->postcategory != null) {
                    $url ='https://atlbha.com/post/' . $post->id . '/' . preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $post->title);
                } else {
                    $url ='https://atlbha.com/page/' . $post->id . '/' . preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $post->title);
                }
            } else {
                $categoryIds = empty($post->page_categories) ? array() : $post->page_categories->pluck('id')->toArray();
                if (in_array(1, $categoryIds)) {
                    $url ='https://' . $post->store->domain . '/blog/post/' . $post->id . '/' . preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $post->title);
                } else {
                    $url ='https://' . $post->store->domain . '/site/SitePages/' . $post->id . '/' . preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $post->title);
                }
            }
            $lastmod = (string) $post->updated_at; // Last modification date
            $priority = '0.8'; // Priority

            $sitemap .= '<url>';
            $sitemap .= '<loc>' . $url . '</loc>';
            $sitemap .= '<lastmod>' . $lastmod . '</lastmod>';
            $sitemap .= '<priority>' . $priority . '</priority>';
            $sitemap .= '</url>';
        }

        $sitemap .='</urlset>';

        $output =  response($sitemap)->header('Content-Type','text/xml');
        return  $output->getContent();
    }

    public function products()
    {
        $products = Product::where('status', 'active')->where('is_deleted', 0)->get();

        $sitemap ='<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($products as $product) {
            if ($product->store_id != null) {
                $url = 'https://' . $product->store->domain . '/shop/product/' . $product->id . '/' . preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $product->name);
            }

            $lastmod = (string) $product->updated_at; // Last modification date
            $priority = '0.8'; // Priority

            $sitemap .= '<url>';
            $sitemap .= '<loc>' . $url . '</loc>';
            $sitemap .= '<lastmod>' . $lastmod . '</lastmod>';
            $sitemap .= '<priority>' . $priority . '</priority>';
            $sitemap .= '</url>';
        }
        $importproducts = Importproduct::whereNot('store_id', null)->where('status', 'active')->get();
        if (count($importproducts) > 0) {
            foreach ($importproducts as $importproduct) {
                $url = 'https://' . $importproduct->store->domain . '/shop/product/' . $importproduct->product->id . '/' . preg_replace('/[^a-zA-Z0-9\x{0621}-\x{064A}]+/u', '-', $importproduct->product->name);
            }

            $lastmod = (string) $importproduct->updated_at; // Last modification date
            $priority = '0.8'; // Priority

            $sitemap .= '<url>';
            $sitemap .= '<loc>' . $url . '</loc>';
            $sitemap .= '<lastmod>' . $lastmod . '</lastmod>';
            $sitemap .= '<priority>' . $priority . '</priority>';
            $sitemap .= '</url>';
        }
        $sitemap .='</urlset>';

        $output =  response($sitemap)->header('Content-Type','text/xml');
        return  $output->getContent();
    }

}
