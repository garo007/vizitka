<?php

namespace App\Services\Admin\Client;

use App\Jobs\AcceptJoinJob;
use App\Models\Client;
use Exception;

class Accept
{
    public function __invoke($clientsIds)
    {
        $clientsIds = $clientsIds['ids'];

        $clients = Client::query()->find($clientsIds)->where('accepted_at', null);

        if (count($clients) !== count($clientsIds)) {
            throw new Exception(__('exception.admin.already_accepted'), 404);
        }

        AcceptJoinJob::dispatch($clientsIds);

        $clients->each->update([
            'accepted_at' => now(),
            'rejected_at' => null
        ]);

        return $clients;
    }
}
