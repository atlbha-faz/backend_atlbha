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
         return preg_match('/^(\d{13})([+-]\d{4})$/', $value);
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
