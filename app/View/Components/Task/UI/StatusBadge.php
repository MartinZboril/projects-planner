<?php

namespace App\View\Components\Task\UI;

use Illuminate\View\Component;
use App\Enums\TaskStatusEnum;
use App\Models\Task;

class StatusBadge extends Component
{
    public $title;
    public $background;
    public $text;

    public function __construct(Task $task, string $text)
    {
        $this->text = $text;
        if ($task->paused) {
            $this->title = __('pages.content.tasks.statuses.stop');
            $this->background = 'danger';
        } elseif ($task->returned) {
            $this->title = __('pages.content.tasks.statuses.return');
            $this->background = 'danger';
        } else {
            switch($task->status) {
                case(TaskStatusEnum::new):
                    $this->title = __('pages.content.tasks.statuses.new');
                    $this->background = 'info';
                    break;
                    
                case(TaskStatusEnum::in_progress):
                    $this->title = __('pages.content.tasks.statuses.in_progress');
                    $this->background = 'warning';
                    break;
        
                case(TaskStatusEnum::complete):
                    $this->title = __('pages.content.tasks.statuses.complete');
                    $this->background = 'success';
                    break;
        
                default:
                    $this->title = 'NaN';
                    $this->background = 'info';
            }
        }
    }

    public function render()
    {
        return view('components.task.ui.status-badge');
    }
}
