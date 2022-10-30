<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\{StoreClientRequest, UpdateClientRequest};
use App\Models\Client;
use App\Services\ClientService;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->middleware('auth');
        $this->clientService = $clientService;
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
        $fields = $request->validated();
        $client = $this->clientService->store($fields);
        $this->clientService->flash('create');

        $redirectAction = isset($fields['create_and_close']) ? 'clients' : 'client';
        return $this->clientService->redirect($redirectAction, $client); 
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
        $fields = $request->validated();
        $client = $this->clientService->update($client, $fields);
        $this->clientService->flash('update');

        $redirectAction = isset($fields['save_and_close']) ? 'clients' : 'client';
        return $this->clientService->redirect($redirectAction, $client); 
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
