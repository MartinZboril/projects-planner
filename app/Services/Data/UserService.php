<?php

namespace App\Services\Data;

use App\Models\Address;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(
        private FileService $fileService,
        private AddressService $addressService,
    ) {
    }

    /**
     * Save data for user.
     */
    public function handleSave(User $user, array $inputs, ?UploadedFile $avatar)
    {
        // Upload avatar
        if ($avatar) {
            $inputs['avatar_id'] = ($this->fileService->handleUpload($avatar, 'users/avatars'))->id;
            $oldAvatarId = $user->avatar_id ?? null;
        }
        // Save users address
        $user->address_id = $this->addressService->handleSave($user->address ?? new Address, [
            'street' => $inputs['street'],
            'house_number' => $inputs['house_number'],
            'city' => $inputs['city'],
            'country' => $inputs['country'],
            'zip_code' => $inputs['zip_code'],
        ]);
        // Modify password fields
        if ($inputs['password'] || ! $user->password) {
            $inputs['password'] = $inputs['password'] ?? ($user->password ?? Str::random(8));
        } else {
            unset($inputs['password']);
        }
        // Store fields
        $user->fill($inputs)->save();
        // Remove old avatar
        if ($oldAvatarId ?? false) {
            $this->fileService->handleRemoveFile($oldAvatarId);
        }

        return $user;
    }

    /**
     * Assign rates to user.
     */
    public function handleAssignRates(User $user, array $inputs): void
    {
        ($user->rates()->count() === 0) ? $user->rates()->attach($inputs['rates']) : $user->rates()->sync($inputs['rates']);
    }
}
