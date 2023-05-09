<?php

namespace App\Services\Client;

use App\Mail\Client\SendEmailResetPassword;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPassword
{
    public function __invoke($data)
    {
        $client = Client::query()->where('email',$data['email'])->first();
        $token = Str::uuid()->toString();
        $resetUrl = config('app.url') . '/client/reset-password?token=' . $token;

        DB::table('password_resets')->insert([
            'email' => $client->email,
            'token' => $token,
            'created_at' => now()
        ]);

        Mail::to($client->email)->send(new SendEmailResetPassword($resetUrl));
    }
}
