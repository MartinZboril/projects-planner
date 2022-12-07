<?php

namespace App\Services\Data;

use App\Models\User;
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
        $user->name = $inputs->name;
        $user->surname = $inputs->surname;
        $user->email = $inputs->email;
        $user->username = $inputs->username;
        $user->job_title = $inputs->job_title;
        $user->password = Hash::make(($inputs->has('password')) ? $inputs->password : Str::random(8));
        $user->mobile = $inputs->mobile;
        $user->phone = $inputs->phone;
        $user->street = $inputs->street;
        $user->house_number = $inputs->house_number;
        $user->city = $inputs->city;
        $user->zip_code = $inputs->zip_code;
        $user->country = $inputs->country;
        $user->save();

        $inputs->user_id = $user->id;
        $inputs->name = $inputs->rate_name;
        $inputs->is_active = true;
        $inputs->value = $inputs->rate_value;

        $rateService = new RateService;
        $rateService->store($inputs);

        return $user;
    }

    /**
     * Update user.
     */
    public function update(User $user, ValidatedInput $inputs): User
    {
        User::where('id', $user->id)
                    ->update([
                        'name' => $inputs->name,
                        'surname' => $inputs->surname,
                        'email' => $inputs->email,
                        'username' => $inputs->username,
                        'job_title' => $inputs->job_title,
                        'password' => $inputs->has('password') ? Hash::make($inputs->password) : $user->password,
                        'mobile' => $inputs->mobile,
                        'phone' => $inputs->phone,
                        'street' => $inputs->street,
                        'house_number' => $inputs->house_number,
                        'city' => $inputs->city,
                        'zip_code' => $inputs->zip_code,
                        'country' => $inputs->country,
                    ]);

        return $user->fresh();
    }

    /**
     * Get route for the action
     */
    public function redirect(string $action, User $user): RedirectResponse
    {
        switch ($action) {
            case 'users':
                return redirect()->route('users.index');
                break;
            case 'user':
                return redirect()->route('users.detail', ['user' => $user]);
                break;
            default:
                return redirect()->back();
        }
    }
}