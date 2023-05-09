<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AirTicketRequest;
use App\Http\Resources\Client\AirTickets\AirTicketResource;
use App\Http\Resources\Client\AirTickets\AirTicketsResource;
use App\Models\AirTicket;
use App\Services\Client\AirTicket\Store;
use App\Services\Client\AirTicket\Update;
use Exception;
use Illuminate\Http\Response;

class AirTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:client']);
    }

    /**
     * @return AirTicketsResource
     */
    public function index(): AirTicketsResource
    {
        $airTickets = AirTicket::query()
            ->where('client_id', auth('client')->user()->id)
            ->orderByDesc('id')
            ->get();

        return (new AirTicketsResource($airTickets));
    }

    /**
     * @param AirTicketRequest $request
     * @param Store $store
     * @return AirTicketsResource|Response
     */
    public function store(AirTicketRequest $request, Store $store): AirTicketsResource|Response
    {
        try {
            $data = $store($request->validated());

            return (new AirTicketsResource($data))->additional([
                'message' => __('response.client.air_ticket.store'),
                'success' => true
            ]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage(), 'success' => false]);
        }
    }

    /**
     * @param AirTicketRequest $request
     * @param AirTicket $airTicket
     * @param Update $store
     * @return AirTicketResource|Response
     */
    public function update(AirTicketRequest $request, AirTicket $airTicket, Update $store): AirTicketResource|Response
    {
        try {
            $data = $store($request->validated(), $airTicket);

            return (new AirTicketResource($data))->additional([
                'message' => __('response.client.air_ticket.update'),
                'success' => true
            ]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage(), 'success' => false]);
        }
    }

    /**
     * @param AirTicket $airTicket
     * @return Response
     */
    public function delete(AirTicket $airTicket): Response
    {
        $airTicket->delete();

        return response(['message' => __('response.client.air_ticket.delete'), 'success' => true]);
    }
}
