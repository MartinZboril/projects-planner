<?php

namespace App\Services\Data;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Support\Str;

class UserService
{
    public function store(array $fields): User
    {
        $user = new User;
        $user->name = $fields['name'];
        $user->surname = $fields['surname'];
        $user->email = $fields['email'];
        $user->username = $fields['username'];
        $user->job_title = $fields['job_title'];
        $user->password = Hash::make(($fields['password']) ? $fields['password'] : Str::random(8));
        $user->mobile = $fields['mobile'];
        $user->phone = $fields['phone'];
        $user->street = $fields['street'];
        $user->house_number = $fields['house_number'];
        $user->city = $fields['city'];
        $user->zip_code = $fields['zip_code'];
        $user->country = $fields['country'];
        $user->save();

        $fields['user_id'] = $user->id;
        $fields['name'] = $fields['rate_name'];
        $fields['is_active'] = true;
        $fields['value'] = $fields['rate_value'];

        $rateService = new RateService;
        $rateService->store($fields);

        return $user;
    }

    public function update(User $user, array $fields): User
    {
        User::where('id', $user->id)
                    ->update([
                        'name' => $fields['name'],
                        'surname' => $fields['surname'],
                        'email' => $fields['email'],
                        'username' => $fields['username'],
                        'job_title' => $fields['job_title'],
                        'password' => ($fields['password']) ? Hash::make($fields['password']) : $user->password,
                        'mobile' => $fields['mobile'],
                        'phone' => $fields['phone'],
                        'street' => $fields['street'],
                        'house_number' => $fields['house_number'],
                        'city' => $fields['city'],
                        'zip_code' => $fields['zip_code'],
                        'country' => $fields['country']
                    ]);

        return $user;
    }

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