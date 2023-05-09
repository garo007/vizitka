<?php

namespace App\Http\Requests\Admin;

use App\Rules\CheckExistsPermissions;
use Illuminate\Foundation\Http\FormRequest;

class SetRolesPermissionsRequest extends FormRequest
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
            'roles' => ['required', 'array'],
            'roles.*' => ['string','exists:roles,name'],
            'permissions' => ['array', new CheckExistsPermissions($this->request->all()['roles'])],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }
}
