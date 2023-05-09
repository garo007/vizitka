<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckExistsPermissions implements Rule
{
    private $rules;

    /**
     * Create a new rule instance.
     *
     * @param $rules
     */
    public function __construct($rules)
    {
        $this->rules = $rules;
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
        if(in_array('seller', $this->rules) && empty($value)){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('exception.client.permissions');
    }
}
