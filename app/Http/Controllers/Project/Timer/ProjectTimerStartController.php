<?php

namespace App\Http\Controllers\Project\Timer;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Timer\StartTimerRequest;
use App\Models\Project;
use App\Traits\FlashTrait;
use App\Services\Data\TimerService;

class ProjectTimerStartController extends Controller
{
    use FlashTrait;

    public function __construct(private TimerService $timerService)
    {
    }

    /**
     * Start working on new timer.
     */
    public function __invoke(StartTimerRequest $request, Project $project): RedirectResponse
    {
        try {
            $this->timerService->handleStart($project->id, $request->rate_id);
            $this->flash(__('messages.timer.start'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.timers.index', [$project]); 
    }
}
