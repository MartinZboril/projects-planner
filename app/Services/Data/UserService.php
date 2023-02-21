<?php

namespace App\Services\Data;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\FileService;

class UserService
{
    /**
     * Save data for user.
     */
    public function handleSave(User $user, array $inputs, ?UploadedFile $avatar)
    {
        if ($avatar) {
            $inputs['avatar_id'] = ((new FileService)->handleUpload($avatar, 'users/avatars'))->id;
            $oldAvatarId = $user->avatar_id ?? null;
        }

        $inputs['password'] = $inputs['password'] ?? ($user->password ?? Str::random(8));
        $user->fill($inputs)->save();

        if ($oldAvatarId ?? false) {
            (new FileService)->handleRemoveFile($oldAvatarId);
        }

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