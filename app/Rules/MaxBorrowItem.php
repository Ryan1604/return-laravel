<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxBorrowItem implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $allowed;
    public $borrowed;

    public function __construct($allowed, $borrowed)
    {
        $this->allowed = $allowed;
        $this->borrowed = $borrowed;
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
        return count($value) <= $this->allowed;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You have been borrowing {$this->borrowed} books, you can only borrow {$this->allowed} books remaining.";
    }
}
