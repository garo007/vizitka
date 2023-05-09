<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Services\Auth\Client\Login;
use Illuminate\Support\Facades\DB;

class ResetPassword
{
    public function __invoke($data)
    {
        $tokenData = DB::table('password_resets')
            ->where('token', $data['token'])->first();

        $client = Client::query()->where('email', $tokenData->email)->first();
        $client->update(['password' => $data['password']]);

        DB::table('password_resets')->where('email', $client->email)->delete();

        $login = app(Login::class);
        return $login(['email' => $client->email, 'password' => $data['password']]);
    }
}
