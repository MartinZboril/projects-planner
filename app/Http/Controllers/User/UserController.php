<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\{StoreUserRequest, UpdateUserRequest};
use App\Models\User;
use App\Traits\FlashTrait;
use App\Services\Data\UserService;

class UserController extends Controller
{
    use FlashTrait;

    public function __construct(private UserService $userService)
    {
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
            $user = $this->userService->handleSave(new User, $request->safe(), $request->file('avatar'));
            $this->flash(__('messages.user.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('users.index')
            : redirect()->route('users.show', $user);
    }

    /**
     * Display the user.
     */
    public function show(User $user): View
    {
        return view('users.show', ['user' => $user]);
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
            $user = $this->userService->handleSave($user, $request->safe(), $request->file('avatar'));
            $this->flash(__('messages.user.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('users.index')
            : redirect()->route('users.show', $user);
    }
}
