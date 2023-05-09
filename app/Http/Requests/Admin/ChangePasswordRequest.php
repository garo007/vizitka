<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'current_password' => ['required', 'string', 'min:8', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'same:confirm_password'],
            'confirm_password' => ['required', 'string', 'min:8', 'max:20'],
        ];
    }
}
