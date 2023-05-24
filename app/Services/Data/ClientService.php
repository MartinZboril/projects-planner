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
    public function handleSave(Client $client, Array $inputs, ?UploadedFile $uploadedFile, ?Array $uploadedFiles=[])
    {
        // Upload logo
        if ($uploadedFile) {
            $inputs['logo_id'] = ((new FileService)->handleUpload($uploadedFile, 'clients/logos'))->id;
            $oldLogoId = $client->logo_id ?? null;
        }
        // Store fields
        $client->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($client, $uploadedFiles);
        }
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
            (new FileService)->handleUpload($uploadedFile, 'clients/files', $client);
        }
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