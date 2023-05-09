<?php

namespace App\Services\Client\AirTicket;

use App\Models\AirTicket;
use App\Models\AirTicketSlice;
use App\Models\AirTicketSliceDetail;
use App\Models\AirTicketSliceDetailAirport;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class Store
{
    public function __invoke($data)
    {
        $ids = DB::transaction(function () use ($data) {
            $ids = [];
            $i = 0;
            while ($i < $data['quantity']) {
                $i++;
                $data['air_ticket_id'] = AirTicket::generateAirTicketId();
                $airTicket = AirTicket::query()->create([
                    'client_id' => auth('client')->user()->id,
                    'type' => $data['type'],
                    'cabin_class' => $data['cabin_class'],
                    'live_mode' => $data['live_mode'],
                    'air_ticket_id' => $data['air_ticket_id'],
                ]);

                $this->createAirTicketSlice($airTicket->id, $data['slices']);
                $ids[] = $airTicket->id;
            }
            return $ids;
        });

        return AirTicket::query()->findMany($ids)->toArray();
    }

    public function createAirTicketSlice($airTicketId, $slices)
    {
        foreach ($slices as $slice) {
            $airTicketSlice = AirTicketSlice::query()->create([
                'air_ticket_id' => $airTicketId,
                'departure_date' => $slice['departure_date']
            ]);

            $this->createAirTicketSliceDetail($airTicketSlice->id, $slice['origin'], 'origin');
            $this->createAirTicketSliceDetail($airTicketSlice->id, $slice['destination'], 'destination');
        }
    }

    public function createAirTicketSliceDetail($airTicketSliceId, $slice, $type)
    {
        $airTicketSliceDetail = AirTicketSliceDetail::query()->create([
            'air_ticket_slice_id' => $airTicketSliceId,
            'type' => $type,
            'name' => $slice['name'],
            'longitude' => $slice['longitude'],
            'latitude' => $slice['latitude'],
            'time_zone' => $slice['time_zone'],
            'icao_code' => $slice['icao_code'],
            'iata_country_code' => $slice['iata_country_code'],
            'iata_code' => $slice['iata_code'],
            'iata_city_code' => $slice['iata_city_code'],
            'city_name' => $slice['city_name'],
        ]);
        $this->createAirTicketSliceDetailAirport($slice['airports'], $airTicketSliceDetail->id);
    }

    public function createAirTicketSliceDetailAirport($airports, $airTicketSliceDetailId)
    {
        foreach ($airports as $airport) {
            $airTicketSliceDetailAirport = AirTicketSliceDetailAirport::query()->create([
                'slice_detail_id' => $airTicketSliceDetailId,
                'name' => $airport['name'],
                'time_zone' => $airport['time_zone'],
                'longitude' => $airport['longitude'],
                'latitude' => $airport['latitude'],
                'icao_code' => $airport['icao_code'],
                'iata_country_code' => $airport['iata_country_code'],
                'iata_code' => $airport['iata_code'],
                'iata_city_code' => $airport['iata_city_code'],
                'city_name' => $airport['city_name'],
            ]);
            $this->createCity($airport['city'], $airTicketSliceDetailAirport->id);
        }
    }

    public function createCity($city, $airport_id)
    {
        City::query()->create([
            'slice_detail_airport_id' => $airport_id,
            'name' => $city['name'],
            'iata_country_code' => $city['iata_country_code'],
            'iata_code' => $city['iata_code']
        ]);
    }
}
