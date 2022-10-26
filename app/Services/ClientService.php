<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientService
{
    public function store(Request $request): Client
    {
        $client = new Client;
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

        return $client;
    }

    public function update(Client $client, Request $request): Client
    {
        Client::where('id', $client->id)
                    ->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'contact_person' => $request->contact_person,
                        'contact_email' => $request->contact_email,
                        'mobile' => $request->mobile,
                        'phone' => $request->phone,
                        'street' => $request->street,
                        'house_number' => $request->house_number,
                        'city' => $request->city,
                        'country' => $request->country,
                        'zip_code' => $request->zip_code,
                        'website' => $request->website,
                        'skype' => $request->skype,
                        'linekedin' => $request->linekedin,
                        'twitter' => $request->twitter,
                        'facebook' => $request->facebook,
                        'instagram' => $request->instagram,
                        'note' => $request->note,
                    ]);

        return $client;
    }

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', __('messages.client.create'));
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', __('messages.client.update'));
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', __('messages.complete'));
                Session::flash('type', 'info');
        }        
    }

    public function redirect(string $action, Client $client): RedirectResponse 
    {
        switch ($action) {
            case 'clients':
                return redirect()->route('clients.index');
                break;
            case 'client':
                return redirect()->route('clients.detail', ['client' => $client]);
                break;
            default:
                return redirect()->back();
        }  
    }
}