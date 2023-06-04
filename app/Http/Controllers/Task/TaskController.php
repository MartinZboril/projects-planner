<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\DataTables\TasksDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\{StoreTaskRequest, UpdateTaskRequest};
use App\Models\Task;
use App\Traits\FlashTrait;
use App\Services\Data\TaskService;

class TaskController extends Controller
{
    use FlashTrait;

    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(TasksDataTable $tasksDataTable): JsonResponse|View
    {
        return $tasksDataTable->with([
            'view' => 'tasks',
        ])->render('tasks.index');
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        try {
            $task = $this->taskService->handleSave(new Task, $request->validated(), $request->file('files'));
            $this->flash(__('messages.task.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tasks.index')
            : redirect()->route('tasks.show', $task);
    }

    /**
     * Display the task.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', ['task' => $task]);
    }

    /**
     * Show the form for editing the task.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', ['task' => $task]);
    }

    /**
     * Update the task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->handleSave($task, $request->validated());
            $this->flash(__('messages.task.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tasks.index')
            : redirect()->route('tasks.show', $task);
    }
}
