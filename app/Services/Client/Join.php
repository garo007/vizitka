<?php

namespace App\Services\Client;

use App\Jobs\JoinJob;
use App\Models\Client;
use Illuminate\Support\Str;

class Join
{
    public function __invoke($data, $fileService)
    {
        $data['join_token'] = Str::uuid()->toString();

        $client = Client::create($data);

        $fileService
            ->setModelId($client->id)
            ->setModelType(Client::class)
            ->setType('state_certificate_of_company')
            ->setDirectory('state-certificate-of-companies')
            ->store($data['state_certificate_of_company']);

        if (!empty($data['license'])) {
            $fileService
                ->setModelId($client->id)
                ->setModelType(Client::class)
                ->setType('license')
                ->setDirectory('licenses')
                ->store($data['license']);
        }

        JoinJob::dispatch($client);

        return __('response.client.join');
    }
}
