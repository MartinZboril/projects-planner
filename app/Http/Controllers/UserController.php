<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }
    
    /**
     * Displays view of users
     */
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
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
            'password' => ['required', 'string', 'nullable', 'min:8'],
            'job_title' => ['string', 'nullable', 'max:255'],
            'mobile' => ['string', 'nullable', 'max:255'],
            'phone' => ['string', 'nullable', 'max:255'],
            'street' => ['string', 'nullable', 'max:255'],
            'house_number' => ['string', 'nullable', 'max:255'],
            'city' => ['string', 'nullable', 'max:255'],
            'country' => ['string', 'nullable', 'max:255'],
            'zip_code' => ['string', 'nullable', 'max:255'],
            'rate_name' => ['required', 'max:255'],
            'rate_value' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $user = $this->userService->store($request);
        $this->userService->flash('create');

        $redirectAction = $request->create_and_close ? 'users' : 'user';
        return $this->userService->redirect($redirectAction, $user); 
    }

    /**
     * Displays detail of user
     */
    public function detail(User $user)
    {
        return view('users.detail', ['user' => $user]);
    }

    /**
     * Displays form for editing user
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update edited user
     */
    public function update(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
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
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $user = $this->userService->update($user, $request);
        $this->userService->flash('update');

        $redirectAction = $request->save_and_close ? 'users' : 'user';
        return $this->userService->redirect($redirectAction, $user);  
    }
}
