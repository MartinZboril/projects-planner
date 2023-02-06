<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Client;
use App\Services\FlashService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\ClientService;
use Illuminate\Http\RedirectResponse;

class ClientMarkController extends Controller
{
    /**
     * Mark selected client.
     */
    public function __invoke(Client $client): RedirectResponse
    {
        try {
            $client = (new ClientService)->mark($client);
            (new FlashService)->flash(__('messages.client.' . ($client->is_marked ? 'mark' : 'unmark')), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
