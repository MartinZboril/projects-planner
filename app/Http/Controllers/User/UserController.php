<?php

namespace App\Http\Controllers\User;

use App\DataTables\RatesDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\Data\UserService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserController extends Controller
{
    use FlashTrait;

    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * Display a listing of the users.
     */
    public function index(UsersDataTable $usersDataTable): JsonResponse|View
    {
        return $usersDataTable->render('users.index');
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $user = $this->userService->handleSave(new User, $request->validated(), $request->file('avatar'));
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
    public function show(User $user, RatesDataTable $ratesDataTable): JsonResponse|View
    {
        return $ratesDataTable->with([
            'user_id' => $user->id,
        ])->render('users.show', ['user' => $user]);
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
            $user = $this->userService->handleSave($user, $request->validated(), $request->file('avatar'));
            $this->flash(__('messages.user.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return $request->has('save_and_close')
            ? redirect()->route('users.index')
            : redirect()->route('users.show', $user);
    }

    /**
     * Remove the user from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->userService->handleDelete($user);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.user.delete'),
        ]);
    }
}
