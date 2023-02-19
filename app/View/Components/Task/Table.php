<?php

namespace App\View\Components\Task;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Task;

class Table extends Component
{
    public $tasks;
    public $tableId;
    public $type;

    public function __construct(Collection $tasks, string $tableId, ?string $type='tasks')
    {
        $this->tasks = $tasks->each(function (Task $task) use($type) {
            $task->edit_route = $this->getEditRoute($task, $type);
            $task->show_route = $this->getShowRoute($task, $type);
        });
        $this->tableId = $tableId;
        $this->type = $type;
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
