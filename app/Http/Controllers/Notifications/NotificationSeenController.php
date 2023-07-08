<?php

namespace App\Http\Controllers\Notifications;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\NotificationService;
use Illuminate\Notifications\DatabaseNotification;

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
