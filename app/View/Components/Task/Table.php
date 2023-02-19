<?php

namespace App\View\Components\Task;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Task;

class Table extends Component
{
    public function __construct(public Collection $tasks, public string $tableId, public ?string $type='tasks')
    {
        $this->tasks->each(function (Task $task) use($type) {
            $task->edit_route = $this->getEditRoute($task, $type);
            $task->show_route = $this->getShowRoute($task, $type);
        });
    }

    private function getEditRoute(?Task $task, string $type): string
    {
        return $type === 'projects'
                ? route('projects.tasks.edit', ['project' => $task->project, 'task' => $task])
                : route('tasks.edit', $task);
    }

    private function getShowRoute(?Task $task, string $type): string
    {
        return $type === 'projects'
                ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task])
                : route('tasks.show', $task);
    }    

    public function render()
    {
        return view('components.task.table');
    }
}
