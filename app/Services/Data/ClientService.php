<?php

namespace App\Services\Data;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;

class ClientService
{
    /**
     * Store new client.
     */
    public function store(array $fields): Client
    {
        $client = new Client;
        $client->name = $fields['name'];
        $client->email = $fields['email'];
        $client->contact_person = $fields['contact_person'];
        $client->contact_email = $fields['contact_email'];
        $client->mobile = $fields['mobile'];
        $client->phone = $fields['phone'];
        $client->street = $fields['street'];
        $client->house_number = $fields['house_number'];
        $client->city = $fields['city'];
        $client->country = $fields['country'];
        $client->zip_code = $fields['zip_code'];
        $client->website = $fields['website'];
        $client->skype = $fields['skype'];
        $client->linekedin = $fields['linekedin'];
        $client->twitter = $fields['twitter'];
        $client->facebook = $fields['facebook'];
        $client->instagram = $fields['instagram'];
        $client->note = $fields['note'];
        $client->save();

        return $client;
    }

    /**
     * Update client.
     */
    public function update(Client $client, array $fields): Client
    {
        Client::where('id', $client->id)
                    ->update([
                        'name' => $fields['name'],
                        'email' => $fields['email'],
                        'contact_person' => $fields['contact_person'],
                        'contact_email' => $fields['contact_email'],
                        'mobile' => $fields['mobile'],
                        'phone' => $fields['phone'],
                        'street' => $fields['street'],
                        'house_number' => $fields['house_number'],
                        'city' => $fields['city'],
                        'country' => $fields['country'],
                        'zip_code' => $fields['zip_code'],
                        'website' => $fields['website'],
                        'skype' => $fields['skype'],
                        'linekedin' => $fields['linekedin'],
                        'twitter' => $fields['twitter'],
                        'facebook' => $fields['facebook'],
                        'instagram' => $fields['instagram'],
                        'note' => $fields['note'],
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