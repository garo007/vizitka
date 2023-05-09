<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\Send;
use Exception;
use Illuminate\Http\Response;

class SendRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:client']);
    }

    /**
     * @param Send $send
     * @return Response
     */
    public function sendRequest(Send $send): Response
    {
        try {
            $client = auth()->guard('client')->user();
            $send($client);

            return response(['message' => __('response.client.send_request'), 'success' => true]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage(), 'success' => false]);
        }
    }
}
