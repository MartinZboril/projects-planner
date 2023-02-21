<?php

namespace App\Services\Data;

use Illuminate\Http\UploadedFile;
use App\Models\{Client, Comment, Note};
use App\Services\FileService;

class ClientService
{
    /**
     * Save data for client.
     */
    public function handleSave(Client $client, Array $inputs, ?UploadedFile $uploadedFile)
    {
        // Upload logo
        if ($uploadedFile) {
            $inputs['logo_id'] = ((new FileService)->handleUpload($uploadedFile, 'clients/logos'))->id;
            $oldLogoId = $client->logo_id ?? null;
        }
        // Store fields
        $client->fill($inputs)->save();
        // Remove old logo
        if ($oldLogoId ?? false) {
            (new FileService)->handleRemoveFile($oldLogoId);
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
        $client->notes()->save($note);
    }

    /**
     * Mark selected client.
     */
    public function handleMark(Client $client): Client
    {
        $client->update(['is_marked' => !$client->is_marked]);
        return $client->fresh();
    }
}