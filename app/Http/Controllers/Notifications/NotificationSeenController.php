<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Services\Data\NotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Log;

class NotificationSeenController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {
    }

    public function __invoke(DatabaseNotification $notification): JsonResponse
    {
        try {
            $this->notificationService->handleSeenNotification($notification);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.notification.read'),
        ]);
    }
}
