<?php

namespace App\Services\Data;

use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use App\Models\{Address, Client, Comment, Note, SocialNetwork};

class ClientService
{
    public function __construct(
        private FileService $fileService,
        private AddressService $addressService,
        private SocialNetworkService $socialNetworkService,
    ) {}

    /**
     * Save data for client.
     */
    public function handleSave(Client $client, Array $inputs, ?UploadedFile $uploadedFile, ?Array $uploadedFiles=[])
    {
        // Upload logo
        if ($uploadedFile) {
            $inputs['logo_id'] = ($this->fileService->handleUpload($uploadedFile, 'clients/logos'))->id;
            $oldLogoId = $client->logo_id ?? null;
        }
        // Save clients address
        $client->address_id = $this->addressService->handleSave($client->address ?? new Address, [
            'street' => $inputs['street'],
            'house_number' => $inputs['house_number'],
            'city' => $inputs['city'],
            'country' => $inputs['country'],
            'zip_code' => $inputs['zip_code'],
        ]);
        // Save clients social network
        $client->social_network_id = $this->socialNetworkService->handleSave($client->socialNetwork ?? new SocialNetwork, [
            'website' => $inputs['website'],
            'skype' => $inputs['skype'],
            'linkedin' => $inputs['linkedin'],
            'twitter' => $inputs['twitter'],
            'facebook' => $inputs['facebook'],
            'instagram' => $inputs['instagram'],
        ]);
        // Store fields
        $client->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($client, $uploadedFiles);
        }
        // Remove old logo
        if ($oldLogoId ?? false) {
            $this->fileService->handleRemoveFile($oldLogoId);
        }

        return $client;
    }

    /**
     * Upload clients files.
     */
    public function handleUploadFiles(Client $client, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->fileService->handleUpload($uploadedFile, 'clients/files', $client);
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