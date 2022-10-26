<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:clients'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'contact_person' => ['string', 'nullable', 'max:255'],
            'contact_email' => ['string', 'nullable', 'max:255'],
            'mobile' => ['string', 'nullable', 'max:255'],
            'phone' => ['string', 'nullable', 'max:255'],
            'street' => ['string', 'nullable', 'max:255'],
            'house_number' => ['string', 'nullable', 'max:255'],
            'city' => ['string', 'nullable', 'max:255'],
            'country' => ['string', 'nullable', 'max:255'],
            'zip_code' => ['string', 'nullable', 'max:255'],
            'website' => ['string', 'nullable'],
            'skype' => ['string', 'nullable'],
            'linekedin' => ['string', 'nullable'],
            'twitter' => ['string', 'nullable'],
            'facebook' => ['string', 'nullable'],
            'instagram' => ['string', 'nullable'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $client = $this->clientService->store($request);
        $this->clientService->flash('create');

        $redirectAction = $request->create_and_close ? 'clients' : 'client';
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255',
                Rule::unique('clients')->ignore($client->id),
            ],
            'email' => ['required', 'string', 'email', 'max:255'],
            'contact_person' => ['string', 'nullable', 'max:255'],
            'contact_email' => ['string', 'nullable', 'max:255'],
            'mobile' => ['string', 'nullable', 'max:255'],
            'phone' => ['string', 'nullable', 'max:255'],
            'street' => ['string', 'nullable', 'max:255'],
            'house_number' => ['string', 'nullable', 'max:255'],
            'city' => ['string', 'nullable', 'max:255'],
            'country' => ['string', 'nullable', 'max:255'],
            'zip_code' => ['string', 'nullable', 'max:255'],
            'website' => ['string', 'nullable'],
            'skype' => ['string', 'nullable'],
            'linekedin' => ['string', 'nullable'],
            'twitter' => ['string', 'nullable'],
            'facebook' => ['string', 'nullable'],
            'instagram' => ['string', 'nullable'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $client = $this->clientService->update($client, $request);
        $this->clientService->flash('update');

        $redirectAction = $request->save_and_close ? 'clients' : 'client';
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
