<?php

namespace App\View\Components\Dashboard;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Enums\{ProjectStatusEnum, TaskStatusEnum, TicketStatusEnum};
use App\Models\{Project, Task, Ticket, ToDo};

class Listing extends Component
{
    public $items;
    public $title;
    public $type;

    public function __construct(Collection $items, string $title, string $type)
    {
        $items->each(function (Project|Task|Ticket|ToDO $item) use($type) {
            if ($type === 'project') {
                $checkRoute = route('projects.change_status', $item);
                $finishStatus = ProjectStatusEnum::finish;
                // assign routes to items
                $item->check_action = "onclick=\"changeProjectStatus('{$checkRoute}', {$finishStatus->value}, 'list', '', '', '')\"";
                $item->edit_route = route('projects.edit', $item);
            } else if ($type === 'task') {
                $checkRoute = route('tasks.change_status', $item);
                $completeStatus = TaskStatusEnum::complete;
                // assign routes to items
                $item->check_action = "onclick=\"changeTaskStatus('{$checkRoute}', {$completeStatus->value}, 'list', '', '', '')\"";
                $item->edit_route = route('tasks.edit', $item);
            } else if ($type === 'ticket') {
                $checkRoute = route('tickets.change_status', $item);
                $closeStatus = TicketStatusEnum::close;
                // assign routes to items
                $item->name = $item->subject;
                $item->check_action = "onclick=\"changeTicketStatus('{$checkRoute}', {$closeStatus->value}, 'list', '', '', '')\"";
                $item->edit_route = route('tickets.edit', $item);
            } else if ($type === 'todo') {
                $checkRoute = route('tasks.todos.check', ['task' => $item->task, 'todo' => $item]);
                // assign routes to items
                $item->check_action = "onclick=\"checkTodo('{$checkRoute}', 'list')\"";
                $item->edit_route = route('tasks.todos.edit', ['task' => $item->task, 'todo' => $item]);
            }
        });
        $this->items = $items;
        $this->title = $title;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.dashboard.listing');
    }
}