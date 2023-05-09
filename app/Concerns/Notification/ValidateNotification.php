<?php

namespace App\Concerns\Notification;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidateNotification
{
    /**
     * Validate action.
     *
     * @param array $data
     * @param $name
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data, $name)
    {
        $validator = Validator::make($data, $this->rules($name));

        if ($validator->fails())
        {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Define validation rules.
     *
     * @param $name
     * @return array
     */
    public function rules($name): array
    {
        $rules = [];

        return $rules[$name];
    }
}
