<?php

namespace App\Services\Auth\Client;

use Exception;
use \Illuminate\Support\Facades\Auth;
Use \Carbon\Carbon;

class Login
{
    public function __invoke($credentials)
    {
        config(['auth.guards.client.driver' => 'session']);
        if(Auth::guard('client')->attempt($credentials)) {
            $data['token'] = Auth::guard('client')
                ->user()
                ->createToken('clientToken', ['*'], Carbon::now()->addDays(3))
                ->plainTextToken;
            $data['expired_at'] = Carbon::now()->addDays(3);

            return $data;
        }
        throw new Exception(__('exception.auth.login_failed'));
    }
}
