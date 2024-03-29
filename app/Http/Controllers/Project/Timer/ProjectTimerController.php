<?php

namespace App\Http\Controllers\Project\Timer;

use App\DataTables\TimersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Timer\StoreTimerRequest;
use App\Http\Requests\Timer\UpdateTimerRequest;
use App\Models\Project;
use App\Models\Timer;
use App\Services\Data\TimerService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProjectTimerController extends Controller
{
    use FlashTrait;

    public function __construct(
        private TimerService $timerService
    ) {
    }

    /**
     * Display the timers of project.
     */
    public function index(Project $project, TimersDataTable $timersDataTable): JsonResponse|View
    {
        return $timersDataTable->with([
            'project_id' => $project->id,
            'view' => 'project',
        ])->render('projects.timers.index', ['project' => $project]);
    }

    /**
     * Show the form for creating a new timer.
     */
    public function create(Project $project): View
    {
        return view('projects.timers.create', ['project' => $project]);
    }

    /**
     * Store a newly created timer in storage.
     */
    public function store(StoreTimerRequest $request, Project $project): RedirectResponse
    {
        try {
            $timer = $this->timerService->handleSave(new Timer, $request->validated());
            $this->flash(__('messages.timer.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.timers.index', ['project' => $project]);
    }

    /**
     * Show the form for editing the timer.
     */
    public function edit(Project $project, Timer $timer): View
    {
        return view('projects.timers.edit', ['project' => $project, 'timer' => $timer]);
    }

    /**
     * Update the timer in storage.
     */
    public function update(UpdateTimerRequest $request, Project $project, Timer $timer): RedirectResponse
    {
        try {
            $timer = $this->timerService->handleSave($timer, $request->validated());
            $this->flash(__('messages.timer.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.timers.index', ['project' => $project]);
    }

    /**
     * Remove the timer from storage.
     */
    public function destroy(Project $project, Timer $timer): JsonResponse
    {
        try {
            $this->timerService->handleDelete($timer);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.timer.delete'),
        ]);
    }
}
