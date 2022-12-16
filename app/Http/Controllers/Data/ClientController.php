<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\{StoreClientRequest, UpdateClientRequest};
use App\Models\{Client, Note};
use App\Services\FlashService;
use App\Services\Data\ClientService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    protected $clientService;
    protected $flashService;

    public function __construct(ClientService $clientService, FlashService $flashService)
    {
        $this->middleware('auth');
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
     * Display the notes of client.
     */
    public function notes(Client $client): View
    {
        return view('clients.notes', ['client' => $client]);
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
            $client = $this->clientService->store($request->safe());
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
    public function detail(Client $client): View
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
            $client = $this->clientService->update($client, $request->safe());
            $this->flashService->flash(__('messages.client.update'), 'info');

            return $this->clientService->setUpRedirect($request->has('save_and_close'), $client);
        } catch (Exception $exception) {
            Log::error($exception);            
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Show the form for creating a new note of client.
     */
    public function createNote(Client $client): View
    {
        return view('clients.note.create', ['client' => $client, 'note' => new Note]);
    }

    /**
     * Show the form for editing the note of client.
     */
    public function editNote(Client $client, Note $note): View
    {
        return view('clients.note.edit', ['client' => $client, 'note' => $note]);
    }    
}
