<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Services\Data\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

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
