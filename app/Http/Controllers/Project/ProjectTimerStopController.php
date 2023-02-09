<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\{Project, Timer};
use App\Traits\FlashTrait;
use App\Services\Data\TimerService;

class ProjectTimerStopController extends Controller
{
    use FlashTrait;

    public function __construct(private TimerService $timerService)
    {
    }

    /**
     * Start working on new timer.
     */
    public function __invoke(Project $project, Timer $timer): RedirectResponse
    {
        try {
            $this->timerService->handleStop($timer);
            $this->flash(__('messages.timer.stop'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.timers.index', [$project]); 
    }
}
