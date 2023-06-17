<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\Data\ClientService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ClientMarkController extends Controller
{
    public function __construct(
        private ClientService $clientService
    ) {
    }

    /**
     * Mark selected client.
     */
    public function __invoke(Client $client): JsonResponse
    {
        try {
            $client = $this->clientService->handleMark($client);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.client.'.($client->is_marked ? 'mark' : 'unmark')),
            'client' => $client,
        ]);
    }
}
