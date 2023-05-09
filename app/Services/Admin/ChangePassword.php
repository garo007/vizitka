<?php

namespace App\Services\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class ChangePassword
{
    public function __invoke($data)
    {
        if (Hash::check($data['current_password'], auth()->guard('admin')->user()->getAuthPassword())) {
            auth()
                ->guard('admin')
                ->user()
                ->update(Arr::only($data, 'password'));
            return auth('admin')->user();
        }

        throw new Exception(json_encode(['current_password' => __('exception.current_password')]));
    }
}
