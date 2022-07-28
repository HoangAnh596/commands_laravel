<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class EmptyValueArrayRule implements ImplicitRule
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
        $lines = json_decode($value, true);
        foreach ($lines as $fields) {
            foreach ($fields as $valueField) {
                $valueField = trim($valueField);
                return !empty($valueField);
            }
        }
        return true;
    }

    public function message()
    {
        return trans('messages.errors.validation.required', [
            'field_title' => trans('fields.checkpoint.' . $this->fieldName)
        ]);
    }
}
