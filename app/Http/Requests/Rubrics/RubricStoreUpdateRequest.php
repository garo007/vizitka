<?php

namespace App\Http\Requests\Rubrics;

use Illuminate\Foundation\Http\FormRequest;

class RubricStoreUpdateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'icon' => ['file', 'required', 'mimes:jpg,jpeg,png,svg'],
        ];
    }
}
