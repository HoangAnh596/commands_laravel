<?php

namespace App\Http\Requests\CheckPoint\Traits;

trait MessageValidationFormTrait
{
    public function generateMessage($fields)
    {
        $messages = [];
        foreach ($fields as $field) {
            $messages[$field.'.required'] = trans(
                'messages.errors.validation.required',
                ['field_title' => trans('fields.checkpoint.' . $field)]
            );

            $messages[$field.'.integer'] = trans(
                'messages.errors.validation.integer',
                ['field_title' => trans('fields.checkpoint.' . $field)]
            );

            $messages[$field.'.min'] = trans(
                'messages.errors.validation.min',
                [
                    'field_title' => trans('fields.checkpoint.' . $field),
                    'min' => config('app.min'),
                ]
            );

            $messages[$field.'.max'] = trans(
                'messages.errors.validation.max',
                [
                    'field_title' => trans('fields.checkpoint.' . $field),
                    'max' => config('app.max'),
                ]
            );
        }
        return $messages;
    }
}
