<?php

namespace App\View\Components\Dashboard;

use App\Enums\ProjectStatusEnum;
use App\Enums\TaskStatusEnum;
use App\Enums\TicketStatusEnum;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Summary extends Component
{
    public $items;

    public $title;

    public $type;

    public function __construct(Collection $items, string $title, string $type)
    {
        $items->each(function (Collection $item) {
            if ($item->get('type') === 'project') {
                $checkRoute = route('projects.change_status', $item->get('item'));
                $finishStatus = ProjectStatusEnum::finish;
                // assign routes to items
                $item['check_action'] = "onclick=\"changeProjectStatus('{$checkRoute}', {$finishStatus->value}, 'summary', '', '', '')\"";
                $item['edit_route'] = route('projects.edit', $item->get('item'));
            } elseif ($item->get('type') === 'task') {
                $checkRoute = route('tasks.change_status', $item->get('item'));
                $completeStatus = TaskStatusEnum::complete;
                // assign routes to items
                $item['check_action'] = "onclick=\"changeTaskStatus('{$checkRoute}', {$completeStatus->value}, 'summary', '', '', '')\"";
                $item['edit_route'] = route('tasks.edit', $item->get('item'));
            } elseif ($item->get('type') === 'ticket') {
                $checkRoute = route('tickets.change_status', $item->get('item'));
                $closeStatus = TicketStatusEnum::close;
                // assign routes to items
                $item['check_action'] = "onclick=\"changeTicketStatus('{$checkRoute}', {$closeStatus->value}, 'summary', '', '', '')\"";
                $item['edit_route'] = route('tickets.edit', $item->get('item'));
            } elseif ($item->get('type') === 'todo') {
                $checkRoute = route('tasks.todos.check', ['task' => $item->get('item')['task'], 'todo' => $item->get('item')]);
                // assign routes to items
                $item['check_action'] = "onclick=\"checkTodo('{$checkRoute}', 'summary')\"";
                $item['edit_route'] = route('tasks.todos.edit', ['task' => $item->get('item')['task'], 'todo' => $item->get('item')]);
            } elseif ($item->get('type') === 'milestone') {
                $item['edit_route'] = route('projects.milestones.edit', ['project' => $item->get('item')['project'], 'milestone' => $item->get('item')]);
                $item['check_action'] = '';
            }
        });
        $this->items = $items;
        $this->title = $title;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.dashboard.summary');
    }
}
