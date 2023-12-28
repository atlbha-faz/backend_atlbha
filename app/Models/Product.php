<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
// use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'for', 'special', 'description', 'stock', 'cover', 'purchasing_price','amount','weight','selling_price', 'quantity', 'less_qty', 'tags', 'discount_price', 'SEOdescription', 'snappixel', 'tiktokpixel', 'twitterpixel', 'instapixel','robot_link' ,'short_description','google_analytics','weight','category_id', 'subcategory_id', 'store_id', 'admin_special','status', 'is_deleted'];
    protected $casts = [
    'weight' => 'float',
    'amount'=>'integer'
];

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function importproduct()
    {
        return $this->hasMany(Importproduct::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function subcategory()
    {
        return Category::whereIn('id', explode(',', $this->subcategory_id))->get();
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }
    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_items',
            'product_id',
            'order_id'

        );
    }

    public function setCoverAttribute($cover)
    {
        if (!is_null($cover)) {
            if (gettype($cover) != 'string') {
                $i = $cover->store('images/product', 'public');
                $this->attributes['cover'] = $cover->hashName();
            } else {
                $this->attributes['cover'] = $cover;
            }
        }
    }

    public function getCoverAttribute($cover)
    {
        if (is_null($cover)) {
            return asset('assets/media/man.png');
        }
        return asset('storage/images/product') . '/' . $cover;
    }
    public function option()
    {
        return $this->hasMany(Option::class);
    }

    
    public function attributes()
    {
       return $this->belongsToMany(
        Attribute::class,
            'attributes_products',
            'product_id',
            'attribute_id',
            )->withPivot("value");
       }
    // public function sluggable(): array
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'name'
    //         ]
    //     ];
    // }
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = $value;
    //     $slug = Str::slug($value);
    //     $count = Product::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->count();
    //     $this->attributes['slug'] = ($count > 0) ? "{$slug}-{$count}" : $slug;

    // }

    public function offers()
    {
        return $this->belongsToMany(
            Offer::class,
            'offers_products',
            'product_id',
            'offer_id'
        )->withPivot("type");
    }
    public function coupons()
    {
        return $this->belongsToMany(
            Coupon::class,
            'coupons_products',
            'product_id',
            'coupon_id'
        );
    }

    public function productrate($product_id)
    {

        return Comment::where('product_id', $product_id)->where('is_deleted', 0)->where('status', 'active')->where('comment_for', 'product')->avg('rateing');
    }
    public function productratecount($product_id)
    {

        return Comment::where('product_id', $product_id)->where('is_deleted', 0)->where('status', 'active')->where('comment_for', 'product')->count();
    }
    //اجمالي المبيعات
    public function getOrderTotal($product_id)
    {

        $product = Product::where('id', $product_id)->first();
        $orders = Order::whereHas('items', function ($q) use ($product) {
            $q->where('product_id', $product->id);
        })->where('order_status', 'completed')->get();

        $sum = 0;
        foreach ($orders as $order) {
            $orderItems = OrderItem::where('order_id', $order->id)->where('product_id', $product->id)->get();

            foreach ($orderItems as $orderItem) {

                if ($order->discount != 0) {
                    $sum = $sum + ($order->discount * $orderItem->total_price / $order->total_price);
                } else {
                    $sum = $sum + $orderItem->total_price;
                }

                //    dd($sum);
            }
        }
        $total = $sum;
        return $total;
    }
// seo files
    // public function setSnappixelAttribute($snappixel)
    // {

    //     if (!is_null($snappixel)) {
    //         if (gettype($snappixel) != 'string') {
    //             $i = $snappixel->store('files/store', 'public');
    //             $this->attributes['snappixel'] = $snappixel->hashName();
    //         } else {
    //             $this->attributes['snappixel'] = $snappixel;
    //         }
    //     }
    // }

    // public function getSnappixelAttribute($snappixel)
    // {

    //     if (is_null($snappixel)) {
    //         return asset('assets/media/test.txt');
    //     }
    //     return asset('storage/files/store') . '/' . $snappixel;
    // }
    // public function setTiktokpixelAttribute($tiktokpixel)
    // {

    //     if (!is_null($tiktokpixel)) {
    //         if (gettype($tiktokpixel) != 'string') {
    //             $i = $tiktokpixel->store('files/store', 'public');
    //             $this->attributes['tiktokpixel'] = $tiktokpixel->hashName();
    //         } else {
    //             $this->attributes['tiktokpixel'] = $tiktokpixel;
    //         }
    //     }
    // }

    // public function getTiktokpixelAttribute($tiktokpixel)
    // {

    //     if (is_null($tiktokpixel)) {
    //         return asset('assets/media/test.txt');
    //     }
    //     return asset('storage/files/store') . '/' . $tiktokpixel;
    // }
    // public function setTwitterpixelAttribute($twitterpixel)
    // {

    //     if (!is_null($twitterpixel)) {
    //         if (gettype($twitterpixel) != 'string') {
    //             $i = $twitterpixel->store('files/store', 'public');
    //             $this->attributes['twitterpixel'] = $twitterpixel->hashName();
    //         } else {
    //             $this->attributes['twitterpixel'] = $twitterpixel;
    //         }
    //     }
    // }

    // public function getTwitterpixelAttribute($twitterpixel)
    // {

    //     if (is_null($twitterpixel)) {
    //         return asset('assets/media/test.txt');
    //     }
    //     return asset('storage/files/store') . '/' . $twitterpixel;
    // }
    // public function setInstapixelAttribute($instapixel)
    // {

    //     if (!is_null($instapixel)) {
    //         if (gettype($instapixel) != 'string') {
    //             $i = $instapixel->store('files/store', 'public');
    //             $this->attributes['instapixel'] = $instapixel->hashName();
    //         } else {
    //             $this->attributes['instapixel'] = $instapixel;
    //         }
    //     }
    // }

    // public function getInstapixelAttribute($instapixel)
    // {

    //     if (is_null($instapixel)) {
    //         return asset('assets/media/test.txt');
    //     }
    //     return asset('storage/files/store') . '/' . $instapixel;
    // }
}
