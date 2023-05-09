<?php

namespace App\Services\Client\AirTicket;

use App\Models\AirTicket;
use Exception;

class Update
{
    public function __invoke($data, $airTicket)
    {
        if ($airTicket->client_id !== auth('client')->user()->id) {
            throw new Exception(__('exception.client.forbidden'));
        }

        $airTicket->update([
            'type' => $data['type'],
            'cabin_class' => $data['cabin_class'],
            'live_mode' => $data['live_mode'],
        ]);

        $this->updateAirTicketSlice($airTicket, $data['slices']);

        return AirTicket::query()->find($airTicket->id);
    }

    public function updateAirTicketSlice($airTicket, $slicesData)
    {
        $airTicket->slices->map(function ($slice) {
            $slice->delete();
        });

        $store = app(Store::class);
        $store->createAirTicketSlice($airTicket->id, $slicesData);
    }
}
