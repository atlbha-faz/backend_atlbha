<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTimestamp implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
         // Regular expression to match the expected format
         $pattern = '/^(\d{13})([+-]\d{4})$/';

         if (preg_match($pattern, $value, $matches)) {
             $milliseconds = (int)$matches[1]; // Extract milliseconds
             $timezoneOffset = $matches[2]; // Extract timezone offset
 
             // Validate milliseconds (should be a positive integer)
             return $milliseconds >= 0 && preg_match('/^[+-]\d{4}$/', $timezoneOffset);
         }
 
         return false; // Does not match the expected format
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'حقل التاريخ غير صحيح.';
    }
}
