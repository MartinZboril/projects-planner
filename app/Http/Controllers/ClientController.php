<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();

        return view('clients.index', ['clients' => $clients]);
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
                    ->route('clients.create')
                    ->withErrors($validator)
                    ->withInput();
        }

        $client = new Client();

        $client->name = $request->name;
        $client->email = $request->email;
        $client->contact_person = $request->contact_person;
        $client->contact_email = $request->contact_email;
        $client->mobile = $request->mobile;
        $client->phone = $request->phone;
        $client->street = $request->street;
        $client->house_number = $request->house_number;
        $client->city = $request->city;
        $client->country = $request->country;
        $client->zip_code = $request->zip_code;
        $client->website = $request->website;
        $client->skype = $request->skype;
        $client->linekedin = $request->linekedin;
        $client->twitter = $request->twitter;
        $client->facebook = $request->facebook;
        $client->instagram = $request->instagram;
        $client->note = $request->note;

        $client->save();

        Session::flash('message', 'Client was created!');
        Session::flash('type', 'info');

        return ($request->create_and_close) ? redirect()->route('clients.index') : redirect()->route('clients.detail', ['client' => $client]);
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
        //
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
        //
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
