<?php

namespace App\Services\Admin\Client;

use App\Jobs\RejectJoinJob;
use App\Models\Client;
use Exception;

class Reject
{
    public function __invoke($clientsIds)
    {
        $clientsIds = $clientsIds['ids'];

        $clients = Client::query()->find($clientsIds)->where('rejected_at', null);

        if (count($clients) !== count($clientsIds)) {
            throw new Exception(__('exception.admin.already_rejected'), 404);
        }

        RejectJoinJob::dispatch($clientsIds);

        $clients->each->update([
            'rejected_at' => now(),
            'accepted_at' => null
        ]);

        return $clients;
    }
}
