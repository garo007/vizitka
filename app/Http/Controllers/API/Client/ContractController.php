<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ContractsResource;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:client']);
    }

    /**
     * @return ContractsResource
     */
    public function index()
    {
        $client = auth()->guard('client')->user();
        $contracts = $client->getContractsAttribute();

        $contracts->map(function ($contract) {
            $contract->setAttribute('expired_at', Carbon::create($contract->created_at)->addMonths(6));
            $contract->setAttribute('status', 'inactive');
        });

        if (count($contracts)){
            $contracts[0]->status = 'active';
        }

        return (new ContractsResource($contracts))->additional(['success' => true]);
    }
}
