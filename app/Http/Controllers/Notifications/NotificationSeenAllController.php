<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Services\Data\NotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class NotificationSeenAllController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        try {
            $this->notificationService->handleSeenAllNotifications();
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.notification.read_all'),
        ]);
    }
}
