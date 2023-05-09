<?php

namespace App\Http\Requests\Auth\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'join_token' => ['required', 'exists:clients'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'same:confirm_password'],
            'confirm_password' => ['required', 'string', 'min:8', 'max:20'],
        ];
    }
}
