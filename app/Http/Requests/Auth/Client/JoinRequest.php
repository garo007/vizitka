<?php

namespace App\Http\Requests\Auth\Client;

use Illuminate\Foundation\Http\FormRequest;

class JoinRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'company_name' => ['string', 'required'],
            'email' => ['email', 'required', 'unique:clients', 'max:150'],
            'business_address' => ['string', 'required'],
            'phone_number' => ['string', 'required'],
            'tin' => ['string', 'required'],
            'bank_account' => ['string', 'required'],
            'bank_name' => ['string', 'required'],
            'contact_person' => ['string', 'required'],
            'position' => ['string', 'required'],
            'state_certificate_of_company' => ['file', 'required', 'mimes:txt,doc,docx,pdf'],
            'license' => ['file', 'nullable', 'mimes:txt,doc,docx,pdf'],
        ];
    }
}
