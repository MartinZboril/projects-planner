<?php

namespace App\Services\Data;

use App\Models\Client;
use App\Services\{FileService, RouteService};
use App\Enums\Routes\ClientRouteEnum;
use Illuminate\Http\{RedirectResponse, UploadedFile};
use Illuminate\Support\ValidatedInput;

class ClientService
{
    /**
     * Save data for client.
     */
    public function save(Client $client, ValidatedInput $inputs, ?UploadedFile $uploadedFile)
    {
        $client = Client::updateOrCreate(
            ['id' => $client->id],
            [
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
            ]
        );

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
        if ($oldLogoId = $client->logo_id) {
            (new FileService)->removeFile($oldLogoId);
        }

        $client->logo_id = ((new FileService)->upload($uploadedFile, 'clients/logos'))->id;
        $client->save();
        return $client;
    }

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect(string $type, Client $client): RedirectResponse
    {
        $redirectAction = $type ? ClientRouteEnum::Index : ClientRouteEnum::Detail;
        $redirectVars = $type ? [] : ['client' => $client];        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}