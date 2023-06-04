<?php

namespace App\Services\Data;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\{Address, User};
use App\Services\FileService;

class UserService
{
    /**
     * Save data for user.
     */
    public function handleSave(User $user, array $inputs, ?UploadedFile $avatar)
    {
        // Upload avatar
        if ($avatar) {
            $inputs['avatar_id'] = ((new FileService)->handleUpload($avatar, 'users/avatars'))->id;
            $oldAvatarId = $user->avatar_id ?? null;
        }
        // Save users address
        $user->address_id = (new AddressService)->handleSave($user->address ?? new Address, [
            'street' => $inputs['street'],
            'house_number' => $inputs['house_number'],
            'city' => $inputs['city'],
            'country' => $inputs['country'],
            'zip_code' => $inputs['zip_code'],
        ]);
        // Store fields
        $inputs['password'] = $inputs['password'] ?? ($user->password ?? Str::random(8));
        $user->fill($inputs)->save();
        // Remove old avatar
        if ($oldAvatarId ?? false) {
            (new FileService)->handleRemoveFile($oldAvatarId);
        }
        // Creare user first rate
        if ($user->rates()->count() === 0) {
            $user->rates()->create([
                'name' => $inputs['rate_name'],
                'is_active' => true,
                'value' => $inputs['rate_value'],
            ]);
        }

        return $user;
    }
}