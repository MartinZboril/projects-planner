<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\DataTables\ClientsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\{StoreClientRequest, UpdateClientRequest};
use App\Models\Client;
use App\Traits\FlashTrait;
use App\Services\Data\ClientService;

class ClientController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ClientService $clientService
    ) {}

    /**
     * Display a listing of the clients.
     */
    public function index(ClientsDataTable $clientsDataTable): JsonResponse|View
    {
        return $clientsDataTable->render('clients.index');
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        try {
            $client = $this->clientService->handleSave(new Client, $request->validated(), $request->file('logo'), $request->file('files'));
            $this->flash(__('messages.client.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
        return $request->has('save_and_close')
                ? redirect()->route('clients.index')
                : redirect()->route('clients.show', $client);
    }

    /**
     * Display the client.
     */
    public function show(Client $client): View
    {
        return view('clients.show', ['client' => $client]);
    }

    /**
     * Show the form for editing the client.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', ['client' => $client]);
    }

    /**
     * Update the client in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        try {
            $client = $this->clientService->handleSave($client, $request->validated(), $request->file('logo'));
            $this->flash(__('messages.client.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);            
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('clients.index')
            : redirect()->route('clients.show', $client);
    }
}