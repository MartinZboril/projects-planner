<?php

namespace App\Services\Data;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\{Str, ValidatedInput};
use App\Models\User;
use App\Services\FileService;

class UserService
{
    /**
     * Save data for user.
     */
    public function handleSave(User $user, ValidatedInput $inputs, ?UploadedFile $avatar)
    {
        $user = User::updateOrCreate(
            ['id' => $user->id],
            [
                'name' => $inputs->name,
                'surname' => $inputs->surname,
                'email' => $inputs->email,
                'username' => $inputs->username,
                'password' => $inputs->password ?? ($user->password ?? Str::random(8)),
                'job_title' => $inputs->job_title,
                'mobile' => $inputs->mobile,
                'phone' => $inputs->phone,
                'street' => $inputs->street,
                'house_number' => $inputs->house_number,
                'city' => $inputs->city,
                'country' => $inputs->country,
                'zip_code' => $inputs->zip_code,
            ]
        );

        if ($avatar) {
            $user = $this->uploadAvatar($user, $avatar);
        }

        if ($user->rates()->count() === 0) {
            $user->rates()->create([
                'name' => $inputs->rate_name,
                'is_active' => true,
                'value' => $inputs->rate_value,
            ]);
        }

        return $user;
    }

    /**
     * Upload users avatar.
     */
    private function uploadAvatar(User $user, ?UploadedFile $avatar): User
    {
        if ($oldAvatarId = $user->avatar_id) {
            (new FileService)->handleRemoveFile($oldAvatarId);
        }

        $user->avatar_id = ((new FileService)->handleUpload($avatar, 'users/avatars'))->id;
        $user->save();

        return $user;
    }
}