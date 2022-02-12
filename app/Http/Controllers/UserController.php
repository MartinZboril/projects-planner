<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Displays view of users
     */
    public function index()
    {
        $users = User::all();

        return view('users.index', ['users' => $users]);
    }

    /**
     * Displays form for creating user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Create new user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['string', 'nullable', 'min:8'],
            'job_title' => ['string', 'nullable', 'max:255'],
            'mobile' => ['string', 'nullable', 'max:255'],
            'phone' => ['string', 'nullable', 'max:255'],
            'street' => ['string', 'nullable', 'max:255'],
            'house_number' => ['string', 'nullable', 'max:255'],
            'city' => ['string', 'nullable', 'max:255'],
            'country' => ['string', 'nullable', 'max:255'],
            'zip_code' => ['string', 'nullable', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('users.create')
                    ->withErrors($validator)
                    ->withInput();
        }

        $user = new User();

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

        Session::flash('message', 'User was created!');
        Session::flash('type', 'info');

        return ($request->save_and_close) ? redirect()->route('users.index') : redirect()->route('users.index');
    }
}
