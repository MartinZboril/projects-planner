<?php

namespace App\Http\Controllers\Project\Timer;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{Auth, Log};
use App\Http\Controllers\Controller;
use App\Models\{Project, Timer};
use App\Services\Data\TimerService;

class ProjectTimerStopController extends Controller
{
    public function __construct(
        private TimerService $timerService
    ) {}

    /**
     * Start working on new timer.
     */
    public function __invoke(Project $project, Timer $timer): JsonResponse
    {
        try {
            $this->timerService->handleStop($timer);
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return response()->json([
            'message' => __('messages.timer.stop'),
            'timer' => $timer,
            'active_timers_count' => Auth::User()->activeTimers->count(),
            'project' => $project,
        ]);
    }
}
