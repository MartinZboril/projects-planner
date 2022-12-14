<?php

namespace App\Services\Data;

use App\Enums\Routes\UserRouteEnum;
use App\Models\User;
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Support\Str;
use Illuminate\Support\ValidatedInput;

class UserService
{
    /**
     * Store new user.
     */
    public function store(ValidatedInput $inputs): User
    {
        $user = new User;
        $user->password = Hash::make(($inputs->has('password')) ? $inputs->password : Str::random(8));

        $user = $this->save($user, $inputs);

        $inputs->user_id = $user->id;
        $inputs->name = $inputs->rate_name;
        $inputs->is_active = true;
        $inputs->value = $inputs->rate_value;

        (new RateService)->store($inputs);

        return $user;
    }

    /**
     * Update user.
     */
    public function update(User $user, ValidatedInput $inputs): User
    {
        $user->password = $inputs->has('password') ? Hash::make($inputs->password) : $user->password;

        return $this->save($user, $inputs);
    }

    /**
     * Save data for user.
     */
    protected function save(User $user, ValidatedInput $inputs)
    {
        $user->name = $inputs->name;
        $user->surname = $inputs->surname;
        $user->email = $inputs->email;
        $user->username = $inputs->username;
        $user->job_title = $inputs->job_title;
        $user->mobile = $inputs->mobile;
        $user->phone = $inputs->phone;
        $user->street = $inputs->street;
        $user->house_number = $inputs->house_number;
        $user->city = $inputs->city;
        $user->zip_code = $inputs->zip_code;
        $user->country = $inputs->country;
        $user->save();

        return $user;
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(string $type, User $user): RedirectResponse
    {
        $redirectAction = $type ? UserRouteEnum::Index : UserRouteEnum::Detail;
        $redirectVars = $type ? [] : ['user' => $user];
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}