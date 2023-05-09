<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Services\Auth\Client\Login;

class CreatePassword
{
    public function __invoke($data)
    {
        $client = Client::firstWhere(['join_token' => $data['join_token']]);

        $client->update([
            'password' => $data['password'],
            'join_token' => null
        ]);

        $login = app(Login::class);

        return $login(['email' => $client->email, 'password' => $data['password']]);
    }
}
