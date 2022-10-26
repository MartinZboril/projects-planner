<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserService
{
    public function store(Request $request): User
    {
        $user = new User;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->job_title = $request->job_title;
        $user->password = Hash::make(($request->password) ? $request->password : Str::random(8));
        $user->mobile = $request->mobile;
        $user->phone = $request->phone;
        $user->street = $request->street;
        $user->house_number = $request->house_number;
        $user->city = $request->city;
        $user->zip_code = $request->zip_code;
        $user->country = $request->country;
        $user->save();

        $request->request->add([
            'user_id' => $user->id,
            'name' => $request->rate_name,
            'is_active' => true,
            'value' => $request->rate_value,
        ]);

        $rateService = new RateService;
        $rateService->store($request);

        return $user;
    }

    public function update(User $user, Request $request): User
    {
        User::where('id', $user->id)
                    ->update([
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'email' => $request->email,
                        'username' => $request->username,
                        'job_title' => $request->job_title,
                        'password' => ($request->password) ? Hash::make($request->password) : $user->password,
                        'mobile' => $request->mobile,
                        'phone' => $request->phone,
                        'street' => $request->street,
                        'house_number' => $request->house_number,
                        'city' => $request->city,
                        'zip_code' => $request->zip_code,
                        'country' => $request->country
                    ]);

        return $user;
    }

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', 'User was created!');
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', 'User was updated!');
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', 'Action was completed!');
                Session::flash('type', 'info');
        }
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