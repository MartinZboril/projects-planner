<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Traits\FlashTrait;
use App\Services\Data\ClientService;

class ClientMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private ClientService $clientService)
    {  
    }

    /**
     * Mark selected client.
     */
    public function __invoke(Client $client): RedirectResponse
    {
        try {
            $client = $this->clientService->handleMark($client);
            $this->flash(__('messages.client.' . ($client->is_marked ? 'mark' : 'unmark')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('clients.show', $client);
    }
}
