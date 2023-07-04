<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Data\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserAvatarRemoveController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user): JsonResponse
    {
        try {
            $this->userService->handleRemoveAvatar($user);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.user.avatar.delete'),
        ]);
    }
}
