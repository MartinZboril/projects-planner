<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\{StoreClientRequest, UpdateClientRequest};
use App\Models\Client;
use App\Services\FlashService;
use App\Services\Data\ClientService;
use Exception;
use Illuminate\Support\Facades\Log;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clients.index', ['clients' => Client::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $fields = $request->validated();
            $client = $this->clientService->store($fields);
            $this->flashService->flash(__('messages.client.create'), 'info');

            $redirectAction = isset($fields['create_and_close']) ? 'clients' : 'client';
            return $this->clientService->redirect($redirectAction, $client);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function detail(Client $client)
    {
        return view('clients.detail', ['client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('clients.edit', ['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        try {
            $fields = $request->validated();
            $client = $this->clientService->update($client, $fields);
            $this->flashService->flash(__('messages.client.update'), 'info');
    
            $redirectAction = isset($fields['save_and_close']) ? 'clients' : 'client';
            return $this->clientService->redirect($redirectAction, $client);         
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
}
