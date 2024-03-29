<?php

namespace App\Http\Controllers\Project\Task;

use App\DataTables\TasksDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\Data\TaskService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProjectTaskController extends Controller
{
    use FlashTrait;

    public function __construct(
        private TaskService $taskService
    ) {
    }

    /**
     * Display the tasks of project.
     */
    public function index(Project $project, TasksDataTable $tasksDataTable): JsonResponse|View
    {
        return $tasksDataTable->with([
            'project_id' => $project->id,
            'view' => 'project',
        ])->render('projects.tasks.index', ['project' => $project]);
    }

    /**
     * Show the form for creating a new task of project.
     */
    public function create(Project $project): View
    {
        return view('projects.tasks.create', ['project' => $project]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        try {
            $task = $this->taskService->handleSave(new Task, $request->validated(), $request->file('files'));
            $this->flash(__('messages.task.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return $request->has('save_and_close')
            ? redirect()->route('projects.tasks.index', $project)
            : redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }

    /**
     * Display the task of project.
     */
    public function show(Project $project, Task $task): View
    {
        return view('projects.tasks.show', ['task' => $task]);
    }

    /**
     * Show the form for editing the task of project.
     */
    public function edit(Project $project, Task $task): View
    {
        return view('projects.tasks.edit', ['task' => $task]);
    }

    /**
     * Update the task in storage.
     */
    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->handleSave($task, $request->validated());
            $this->flash(__('messages.task.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return $request->has('save_and_close')
            ? redirect()->route('projects.tasks.index', $project)
            : redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }
}
