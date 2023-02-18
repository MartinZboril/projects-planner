<?php

namespace App\Services\Data;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\ValidatedInput;
use App\Models\{Client, Comment, Note};
use App\Services\FileService;

class ClientService
{
    /**
     * Save data for client.
     */
    public function handleSave(Client $client, ValidatedInput $inputs, ?UploadedFile $uploadedFile)
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
     * Upload clients files.
     */
    public function handleUploadFiles(Client $client, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $client->files()->save((new FileService)->handleUpload($uploadedFile, 'clients/files'));
        }
    }
    
    /**
     * Save clients comments.
     */        
    public function handleSaveComment(Client $client, Comment $comment): void
    {
        $client->comments()->save($comment);
    }

    /**
     * Save clients notes.
     */
    public function handleSaveNote(Client $client, Note $note): void
    {
        $client->comments()->save($note);
    }

    /**
     * Mark selected client.
     */
    public function handleMark(Client $client): Client
    {
        $client->is_marked = !$client->is_marked;
        $client->save();
        return $client;
    }
    
    /**
     * Store client logo.
     */
    private function storeLogo(Client $client, UploadedFile $uploadedFile): Client
    {   
        if ($oldLogoId = $client->logo_id) {
            (new FileService)->handleRemoveFile($oldLogoId);
        }

        $client->logo_id = ((new FileService)->handleUpload($uploadedFile, 'clients/logos'))->id;
        $client->save();
        return $client;
    }
}