<?php

namespace App\Services\Auth\Admin;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class Login
{
    public function __invoke($credentials)
    {
        config(['auth.guards.admin.driver' => 'session']);
        if(Auth::guard('admin')->attempt($credentials)) {
            $data['token'] = Auth::guard('admin')
                ->user()
                ->createToken('adminToken', ['*'], Carbon::now()->addDays(3))
                ->plainTextToken;
            $data['expired_at'] = Carbon::now()->addDays(3);

            return $data;
        }
        throw new Exception(__('exception.admin.login'));
    }
}
