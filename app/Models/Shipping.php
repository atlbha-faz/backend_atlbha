<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'price', 'store_id', 'city', 'streetaddress', 'sticker', 'shipping_id', 'track_id', 'district', 'shipping_type', 'pickup_date',
        'destination_city', 'destination_district', 'destination_streetaddress', 'shippingtype_id', 'weight', 'quantity', 'customer_id', 'order_id', 'is_deleted'];
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

    public function convertTimestamp($timestamp)
    {
        if ($timestamp == null) {
            return null;
        }
        // Initialize variables
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
            die("Invalid timestamp format.");
        }

        // Convert milliseconds to seconds
        $seconds = $milliseconds / 1000;

        // Calculate timezone offset in seconds
        $offsetHours = (int) substr($timezoneOffset, 0, 3);
        $offsetMinutes = (int) (isset($timezoneOffset[3]) ? substr($timezoneOffset, 3, 2) : 0); // Handle minutes
        $offsetInSeconds = ($offsetHours * 3600) + ($offsetMinutes * 60);

        // Adjust seconds based on timezone offset
        $adjustedSeconds = $seconds + $offsetInSeconds;

        // Get the date components
        $dateComponents = getdate($adjustedSeconds);

        // Map timezone offsets to timezone names
        $timezoneNames = [
            '+0300' => 'Eastern European Summer Time',
            '-0500' => 'Central Standard Time',
            // Add more timezone mappings as needed
        ];

        // Get the timezone name
        $timezoneName = $timezoneNames[$timezoneOffset] ?? 'Unknown Timezone';

        // Format the date
        $formattedDate = sprintf(
            '%s %s %02d %d %02d:%02d:%02d GMT%s%02d%02d (%s)',
            $dateComponents['weekday'], // Day of the week
            $dateComponents['month'], // Month
            $dateComponents['mday'], // Day of the month
            $dateComponents['year'], // Year
            $dateComponents['hours'], // Hours
            $dateComponents['minutes'], // Minutes
            $dateComponents['seconds'], // Seconds
            $timezoneOffset[0], // GMT sign (+ or -)
            abs($offsetHours), // Absolute hours
            abs($offsetMinutes), // Absolute minutes
            $timezoneName // Full timezone name
        );

        return $formattedDate;
    }

}
