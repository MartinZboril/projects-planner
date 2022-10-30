<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\{StoreUserRequest, UpdateUserRequest};
use App\Models\User;
use App\Services\UserService;

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
    public function store(StoreUserRequest $request)
    {
        $fields = $request->validated();

        $user = $this->userService->store($fields);
        $this->userService->flash('create');

        $redirectAction = isset($fields['create_and_close']) ? 'users' : 'user';
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
    public function update(User $user, UpdateUserRequest $request)
    {
        $fields = $request->validated();

        $user = $this->userService->update($user, $fields);
        $this->userService->flash('update');

        $redirectAction = isset($fields['save_and_close']) ? 'users' : 'user';
        return $this->userService->redirect($redirectAction, $user);  
    }
}
