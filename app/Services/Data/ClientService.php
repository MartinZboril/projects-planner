<?php

namespace App\Services\Data;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

class ClientService
{
    /**
     * Store new client.
     */
    public function store(ValidatedInput $inputs): Client
    {
        $client = new Client;
        $client->name = $inputs->name;
        $client->email = $inputs->email;
        $client->contact_person = $inputs->contact_person;
        $client->contact_email = $inputs->contact_email;
        $client->mobile = $inputs->mobile;
        $client->phone = $inputs->phone;
        $client->street = $inputs->street;
        $client->house_number = $inputs->house_number;
        $client->city = $inputs->city;
        $client->country = $inputs->country;
        $client->zip_code = $inputs->zip_code;
        $client->website = $inputs->website;
        $client->skype = $inputs->skype;
        $client->linekedin = $inputs->linekedin;
        $client->twitter = $inputs->twitter;
        $client->facebook = $inputs->facebook;
        $client->instagram = $inputs->instagram;
        $client->note = $inputs->note;
        $client->save();

        return $client;
    }

    /**
     * Update client.
     */
    public function update(Client $client, ValidatedInput $inputs): Client
    {
        Client::where('id', $client->id)
                    ->update([
                        'name' => $inputs->name,
                        'email' => $inputs->email,
                        'contact_person' => $inputs->contact_person,
                        'contact_email' => $inputs->contact_email,
                        'mobile' => $inputs->mobile,
                        'phone' => $inputs->phone,
                        'street' => $inputs->street,
                        'house_number' => $inputs->house_number,
                        'city' => $inputs->city,
                        'country' => $inputs->country,
                        'zip_code' => $inputs->zip_code,
                        'website' => $inputs->website,
                        'skype' => $inputs->skype,
                        'linekedin' => $inputs->linekedin,
                        'twitter' => $inputs->twitter,
                        'facebook' => $inputs->facebook,
                        'instagram' => $inputs->instagram,
                        'note' => $inputs->note,
                    ]);

        return $client;
    }

    /**
     * Get route for the action
     */
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