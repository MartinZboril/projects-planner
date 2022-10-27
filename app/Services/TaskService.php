<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TaskService
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    public function store(Request $request): Task
    {
        $task = new Task;
        $task->name = $request->name;
        $task->project_id = $request->project_id;
        $task->milestone_id = $request->milestone_id ? $request->milestone_id : null;
        $task->status_id = 1;
        $task->author_id = Auth::id();
        $task->user_id = $request->user_id;
        $task->start_date = $request->start_date;
        $task->due_date = $request->due_date;
        $task->description = $request->description;
        $task->save();

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->author_id)) {
            $this->projectUserService->store($task->project_id, $task->author_id);
        }

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->user_id)) {
            $this->projectUserService->store($task->project_id, $task->user_id);
        }

        return $task;
    }

    public function update(Task $task, Request $request): Task
    {
        Task::where('id', $task->id)
                    ->update([
                        'name' => $request->name,
                        'project_id' => $request->project_id,
                        'milestone_id' => $request->milestone_id ? $request->milestone_id : null,
                        'user_id' => $request->user_id,
                        'start_date' => $request->start_date,
                        'due_date' => $request->due_date,
                        'description' => $request->description,
                    ]);

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->author_id)) {
            $this->projectUserService->store($task->project_id, $task->author_id);
        }

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->user_id)) {
            $this->projectUserService->store($task->project_id, $task->user_id);
        }

        return $task;
    }
    
    public function change(Task $task, Request $request): Task
    {
        Task::where('id', $task->id)
                    ->update([
                        'status_id' => $request->status_id,
                        'is_returned' => false,
                    ]);

        return $task;
    }
        
    public function pause(Task $task, Request $request): Task
    {
        Task::where('id', $task->id)
                ->update([
                    'is_stopped' => $request->action,
                ]);

        return $task;
    }

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', __('messages.task.create'));
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', __('messages.task.update'));
                Session::flash('type', 'info');
                break;
            case 'working':
                Session::flash('message', __('messages.task.working'));
                Session::flash('type', 'info');
                break;
            case 'complete':
                Session::flash('message', __('messages.task.complete'));
                Session::flash('type', 'info');
                break;
            case 'return':
                Session::flash('message', __('messages.task.return'));
                Session::flash('type', 'info');
                break;
            case 'stop':
                Session::flash('message', __('messages.task.stop'));
                Session::flash('type', 'info');
                break;
            case 'resume':
                Session::flash('message', __('messages.task.resume'));
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', __('messages.complete'));
                Session::flash('type', 'info');
        }
    }

    public function redirect(string $action, Task $task): RedirectResponse 
    {   
        switch ($action) {
            case 'tasks':
                return redirect()->route('tasks.index');
                break;
            case 'task':
                return redirect()->route('tasks.detail', ['task' => $task->id]);
                break;
            case 'project_tasks':
                return redirect()->route('projects.tasks', ['project' => $task->project]);
                break;
            case 'project_task':
                return redirect()->route('projects.task.detail', ['project' => $task->project, 'task' => $task]);
                break;
            case 'kanban':
                return redirect()->route('projects.kanban', ['project' => $task->project]);
                break;
            default:
                return redirect()->back();
        }
    }
}