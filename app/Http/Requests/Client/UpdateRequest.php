<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('client')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'company_name' => ['string', 'required'],
            'email' => ['email', 'required', 'max:150'],
            'business_address' => ['string', 'required'],
            'phone_number' => ['string', 'required'],
            'tin' => ['string', 'required'],
            'bank_account' => ['string', 'required'],
            'bank_name' => ['string', 'required'],
            'contact_person' => ['string', 'required'],
            'position' => ['string', 'required'],
            'description' => ['string', 'nullable'],
            'state_certificate_of_company' => ['file', 'nullable', 'mimes:txt,doc,docx,pdf'],
            'license' => ['file', 'nullable', 'mimes:txt,doc,docx,pdf'],
            'privacy_policy' => ['file', 'nullable', 'mimes:txt,doc,docx,pdf'],
            'logo' => ['file', 'nullable', 'mimes:jpg,jpeg,png,svg'],
        ];
    }
}
