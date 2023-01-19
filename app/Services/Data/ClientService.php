<?php

namespace App\Services\Data;

use App\Models\File;
use App\Models\Client;
use App\Services\FileService;
use App\Services\RouteService;
use Illuminate\Http\UploadedFile;
use App\Enums\Routes\ClientRouteEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

class ClientService
{
    /**
     * Store new client.
     */
    public function store(ValidatedInput $inputs, ?UploadedFile $uploadedFile): Client
    {
        return $this->save(new Client, $inputs, $uploadedFile);
    }

    /**
     * Update client.
     */
    public function update(Client $client, ValidatedInput $inputs, ?UploadedFile $uploadedFile): Client
    {
        return $this->save($client, $inputs, $uploadedFile);
    }

    /**
     * Save data for client.
     */
    protected function save(Client $client, ValidatedInput $inputs, ?UploadedFile $uploadedFile)
    {
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

        if ($uploadedFile) {
            $client = ($this->storeLogo($client, $uploadedFile));
        }

        return $client;
    }
    
    /**
     * Mark selected client.
     */
    public function mark(Client $client): Client
    {
        $client->is_marked = !$client->is_marked;
        $client->save();

        return $client;
    }
    
    /**
     * Store client logo.
     */
    public function storeLogo(Client $client, UploadedFile $uploadedFile): Client
    {   
        $oldLogoId = $client->logo_id;

        $client->logo_id = ((new FileService)->upload($uploadedFile, 'clients/logos'))->id;
        $client->save();

        if ($oldLogoId) {
            unlink(public_path('storage/' . File::find($oldLogoId)->path));
            File::destroy($oldLogoId);    
        }

        return $client;
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(string $type, Client $client): RedirectResponse
    {
        $redirectAction = $type ? ClientRouteEnum::Index : ClientRouteEnum::Detail;
        $redirectVars = $type ? [] : ['client' => $client];
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}