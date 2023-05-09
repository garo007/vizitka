<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class AirTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('client')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'type' => ['required', 'in:one-way,round-trip,multi-city'],
            'cabin_class' => ['required', 'in:first,business,premium_economy,economy'],
            'live_mode' => ['required', 'boolean'],
            'slices' => ['required', 'array'],
            'slices.*.departure_date' => ['required', 'date_format:Y-m-d'],
            'slices.*.origin' => ['required', 'array'],
            'slices.*.origin.city_name' => ['required', 'string'],
            'slices.*.origin.iata_city_code' => ['nullable', 'string'],
            'slices.*.origin.iata_code' => ['nullable', 'string', 'min:3', 'max:3'],
            'slices.*.origin.iata_country_code' => ['required', 'string', 'min:2', 'max:2'],
            'slices.*.origin.icao_code' => ['required', 'string'],
            'slices.*.origin.latitude' => ['required', 'numeric'],
            'slices.*.origin.longitude' => ['required', 'numeric'],
            'slices.*.origin.name' => ['required', 'string'],
            'slices.*.origin.time_zone' => ['required', 'string'],

            'slices.*.origin.airports' => ['required', 'array'],
            'slices.*.origin.airports.*.city_name' => ['required', 'string'],
            'slices.*.origin.airports.*.iata_city_code' => ['nullable', 'string'],
            'slices.*.origin.airports.*.iata_code' => ['nullable', 'string', 'min:3', 'max:3'],
            'slices.*.origin.airports.*.iata_country_code' => ['required', 'string', 'min:2', 'max:2'],
            'slices.*.origin.airports.*.icao_code' => ['required', 'string', 'min:4', 'max:4'],
            'slices.*.origin.airports.*.latitude' => ['required', 'numeric'],
            'slices.*.origin.airports.*.longitude' => ['required', 'numeric'],
            'slices.*.origin.airports.*.name' => ['required', 'string'],
            'slices.*.origin.airports.*.time_zone' => ['required', 'string'],
            'slices.*.origin.airports.*.city' => ['required', 'array'],
            'slices.*.origin.airports.*.city.iata_code' => ['nullable', 'string', 'min:3', 'max:3'],
            'slices.*.origin.airports.*.city.iata_country_code' => ['required', 'string', 'min:2', 'max:2'],
            'slices.*.origin.airports.*.city.name' => ['required', 'string'],

            'slices.*.destination' => ['required', 'array'],
            'slices.*.destination.city_name' => ['required', 'string'],
            'slices.*.destination.iata_city_code' => ['nullable', 'string'],
            'slices.*.destination.iata_code' => ['nullable', 'string', 'min:3', 'max:3'],
            'slices.*.destination.iata_country_code' => ['required', 'string', 'min:2', 'max:2'],
            'slices.*.destination.icao_code' => ['required', 'string'],
            'slices.*.destination.latitude' => ['required', 'numeric'],
            'slices.*.destination.longitude' => ['required', 'numeric'],
            'slices.*.destination.name' => ['required', 'string'],
            'slices.*.destination.time_zone' => ['required', 'string'],

            'slices.*.destination.airports' => ['required', 'array'],
            'slices.*.destination.airports.*.city_name' => ['required', 'string'],
            'slices.*.destination.airports.*.iata_city_code' => ['nullable', 'string'],
            'slices.*.destination.airports.*.iata_code' => ['nullable', 'string', 'min:3', 'max:3'],
            'slices.*.destination.airports.*.iata_country_code' => ['required', 'string', 'min:2', 'max:2'],
            'slices.*.destination.airports.*.icao_code' => ['required', 'string', 'min:4', 'max:4'],
            'slices.*.destination.airports.*.latitude' => ['required', 'numeric'],
            'slices.*.destination.airports.*.longitude' => ['required', 'numeric'],
            'slices.*.destination.airports.*.name' => ['required', 'string'],
            'slices.*.destination.airports.*.time_zone' => ['required', 'string'],
            'slices.*.destination.airports.*.city' => ['required', 'array'],
            'slices.*.destination.airports.*.city.iata_code' => ['nullable', 'string', 'min:3', 'max:3'],
            'slices.*.destination.airports.*.city.iata_country_code' => ['required', 'string', 'min:2', 'max:2'],
            'slices.*.destination.airports.*.city.name' => ['required', 'string'],
        ];
    }
}
