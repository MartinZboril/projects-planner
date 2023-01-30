<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\{StoreUserRequest, UpdateUserRequest};
use App\Models\User;
use App\Services\FlashService;
use App\Services\Data\UserService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
     * Display a listing of the users.
     */
    public function index(): View
    {
        return view('users.index', ['users' => User::all()]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('users.create', ['user' => new User]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $user = $this->userService->store($request->safe(), $request->file('avatar'));
            $this->flashService->flash(__('messages.user.create'), 'info');

            return $this->userService->setUpRedirect($request->has('save_and_close'), $user);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Display the user.
     */
    public function detail(User $user): View
    {
        return view('users.detail', ['user' => $user]);
    }

    /**
     * Show the form for editing the user.
     */
    public function edit(User $user): View
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the user in storage.
     */
    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {
        try {
            $user = $this->userService->update($user, $request->safe(), $request->file('avatar'));
            $this->flashService->flash(__('messages.user.update'), 'info');

            return $this->userService->setUpRedirect($request->has('save_and_close'), $user);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
