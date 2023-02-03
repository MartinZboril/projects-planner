<?php

namespace App\Services\Data;

use App\Models\Rate;
use App\Models\User;
use Illuminate\Support\Str;
use App\Services\FileService;
use App\Services\RouteService;
use Illuminate\Http\UploadedFile;
use App\Enums\Routes\UserRouteEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\{Auth, Hash};

class UserService
{
    /**
     * Save data for user.
     */
    public function save(User $user, ValidatedInput $inputs, ?UploadedFile $avatar)
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
            $inputs->user_id = $user->id;
            $inputs->name = $inputs->rate_name;
            $inputs->is_active = true;
            $inputs->value = $inputs->rate_value;
    
            (new RateService)->save(new Rate, $inputs);
        }

        return $user;
    }

    /**
     * Upload users avatar.
     */
    public function uploadAvatar(User $user, ?UploadedFile $avatar): User
    {
        if ($oldAvatarId = $user->avatar_id) {
            (new FileService)->removeFile($oldAvatarId);
        }

        $user->avatar_id = ((new FileService)->upload($avatar, 'users/avatars'))->id;
        $user->save();

        return $user;
    }

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect(string $type, User $user): RedirectResponse
    {
        $redirectAction = $type ? UserRouteEnum::Index : UserRouteEnum::Detail;
        $redirectVars = $type ? [] : ['user' => $user];
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}