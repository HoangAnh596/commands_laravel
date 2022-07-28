<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class EmptyValueRule implements ImplicitRule
{

    protected $fieldName;

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     *
     * @SuppressWarnings("unused")
     * @return boolean
     */
    public function passes($attribute, $value)
    {
        $value = trim($value);
        return !empty($value);
    }

    public function message()
    {
        return trans('messages.errors.validation.required', [
            'field_title' => trans('fields.checkpoint.' . $this->fieldName)
        ]);
    }
}
