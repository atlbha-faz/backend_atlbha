<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = ['description','price','store_id','city','streetaddress', 'sticker','shipping_id','track_id','district','shipping_type','pickup_date',
    'destination_city','destination_district','destination_streetaddress','shippingtype_id','weight','quantity','customer_id','order_id','is_deleted'];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function convertTimestamp($time){
        if($time == null){
            return null;
        }
        $timestamp = $time;
        $milliseconds = 0;
        $timezoneOffset = '';
    
        // Check for '+' in the timestamp
        if (strpos($timestamp, '+') !== false) {
            list($milliseconds, $timezoneOffset) = explode('+', $timestamp);
        } 
        // Check for '-' in the timestamp
        elseif (strpos($timestamp, '-') !== false) {
            list($milliseconds, $timezoneOffset) = explode('-', $timestamp);
            $timezoneOffset = '-' . $timezoneOffset; // Reattach the minus sign
        } 
        // Handle invalid format
        else {
            return null;
        }
    
        // Convert milliseconds to seconds
        $seconds = $milliseconds / 1000;
    
        // Calculate timezone offset in seconds
        $offsetHours = (int)substr($timezoneOffset, 0, 3);
        $offsetMinutes = (int)(isset($timezoneOffset[3]) ? substr($timezoneOffset, 3, 2) : 0); // Handle minutes
        $offsetInSeconds = ($offsetHours * 3600) + ($offsetMinutes * 60);
    
        // Adjust seconds based on timezone offset
        $adjustedSeconds = $seconds + $offsetInSeconds;
    
        // Format the adjusted date
        $formattedDate = gmdate('Y-m-d H:i:s', $adjustedSeconds);
    
        return $formattedDate . ' ' . $timezoneOffset; 
    }


}
