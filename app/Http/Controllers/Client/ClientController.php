<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Comment;
use Illuminate\View\View;
use App\Models\{Client, Note};
use App\Services\FlashService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\ClientService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Client\{StoreClientRequest, MarkClientRequest, UpdateClientRequest};

class ClientController extends Controller
{
    protected $clientService;
    protected $flashService;

    public function __construct(ClientService $clientService, FlashService $flashService)
    {
        $this->clientService = $clientService;
        $this->flashService = $flashService;
    }

    /**
     * Display a listing of the clients.
     */
    public function index(): View
    {
        return view('clients.index', ['clients' => Client::all()]);
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        return view('clients.create', ['client' => new Client]);
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        try {
            $client = $this->clientService->save(new Client, $request->safe(), $request->file('logo'));
            $this->flashService->flash(__('messages.client.create'), 'info');
            return $this->clientService->setUpRedirect($request->has('save_and_close'), $client);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
    }

    /**
     * Display the client.
     */
    public function show(Client $client): View
    {
        return view('clients.detail', ['client' => $client]);
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
            $client = $this->clientService->save($client, $request->safe(), $request->file('logo'));
            $this->flashService->flash(__('messages.client.update'), 'info');
            return $this->clientService->setUpRedirect($request->has('save_and_close'), $client);
        } catch (Exception $exception) {
            Log::error($exception);            
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}