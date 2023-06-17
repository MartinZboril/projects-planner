<?php

namespace App\Http\Controllers\Project\Timer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Timer\StartTimerRequest;
use App\Models\Project;
use App\Services\Data\TimerService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectTimerStartController extends Controller
{
    public function __construct(
        private TimerService $timerService
    ) {
    }

    /**
     * Start working on new timer.
     */
    public function __invoke(StartTimerRequest $request, Project $project): JsonResponse
    {
        try {
            $timer = $this->timerService->handleStart($project->id, $request->rate_id);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.timer.start'),
            'active_timers' => Auth::User()->activeTimers,
            'timer' => $timer,
            'project' => $project,
        ]);
    }
}
