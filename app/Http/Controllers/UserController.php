<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\{StoreUserRequest, UpdateUserRequest};
use App\Models\User;
use App\Services\FlashService;
use App\Services\Data\UserService;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;
    protected $flashService;

    public function __construct(UserService $userService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
        $this->flashService = $flashService;
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
        try {
            $fields = $request->validated();
            $user = $this->userService->store($fields);
            $this->flashService->flash(__('messages.user.create'), 'info');

            $redirectAction = isset($request->create_and_close) ? 'users' : 'user';
            return $this->userService->redirect($redirectAction, $user); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
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
        try {
            $fields = $request->validated();
            $user = $this->userService->update($user, $fields);
            $this->flashService->flash(__('messages.user.update'), 'info');

            $redirectAction = isset($request->save_and_close) ? 'users' : 'user';
            return $this->userService->redirect($redirectAction, $user);  
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
