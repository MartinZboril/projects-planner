<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\ClientService;

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
            $this->clientService->handleRemoveAvatar($client);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.client.logo.delete'),
        ]);
    }
}
