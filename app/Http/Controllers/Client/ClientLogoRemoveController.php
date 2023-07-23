<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\Data\ClientService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ClientLogoRemoveController extends Controller
{
    public function __construct(
        private ClientService $clientService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Client $client): JsonResponse
    {
        try {
            $this->clientService->handleRemoveLogo($client);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.client.logo.delete'),
        ]);
    }
}
