<?php

namespace App\Services\Client;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class ChangePassword
{
    public function __invoke($data)
    {
        $client = auth()->guard('client')->user();

        if (Hash::check($data['current_password'], $client->getAuthPassword())) {
            $client->update(Arr::only($data, 'password'));
            return $client;
        }

        throw new Exception(json_encode(['current_password' => __('exception.current_password')]));
    }
}
