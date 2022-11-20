<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToDo\{CheckToDoRequest, StoreToDoRequest, UpdateToDoRequest};
use App\Models\{Task, ToDo};
use App\Services\FlashService;
use App\Services\Data\ToDoService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ToDoController extends Controller
{  
    protected $todoService;
    protected $flashService;

    public function __construct(ToDoService $todoService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->todoService = $todoService;
        $this->flashService = $flashService;
    }

    /**
     * Show the form for creating a new todo.
     */
    public function create(Task $task): View
    {
        return view('todos.create', ['task' => $task]);
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(StoreToDoRequest $request): RedirectResponse
    {
        try {
            $fields = $request->validated();
            $todo = $this->todoService->store($fields);
            $this->flashService->flash(__('messages.todo.create'), 'info');

            $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'task';
            return $this->todoService->redirect($redirectAction, $todo);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Show the form for editing the todo.
=    */
    public function edit(Task $task, ToDo $todo): View
    {
        return view('todos.edit', ['task' => $task, 'todo' => $todo]);
    }

    /**
     * Update the todo in storage.
     */
    public function update(UpdateToDoRequest $request, ToDo $todo): RedirectResponse
    {
        try {
            $fields = $request->validated();
            $todo = $this->todoService->update($todo, $fields);
            $this->flashService->flash(__('messages.todo.update'), 'info');

            $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'task';
            return $this->todoService->redirect($redirectAction, $todo);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Check the todo in storage.
=     */
    public function check(CheckToDoRequest $request, ToDo $todo): RedirectResponse
    {
        try {
            $fields = $request->validated();
            $todo = $this->todoService->check($todo, $fields);
            $this->flashService->flash(__('messages.todo.' . ($fields['action'] ? ToDo::FINISH : ToDo::RETURN)), 'info');


            $redirectAction =  $fields['redirect'] == 'dashboard_task' ? 'dashboard_task' : ((($fields['redirect'] == 'projects') ? 'project_' : '') . 'task');
            return $this->todoService->redirect($redirectAction, $todo);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
