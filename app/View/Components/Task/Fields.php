<?php

namespace App\View\Components\Task;

use Illuminate\View\Component;
use App\Models\{Project, User};

class Fields extends Component
{
    public $task;
    public $type;

    public function __construct($task, $type)
    {
        $this->task = $task;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.task.fields', ['projects' => Project::all(), 'users' => User::all()]);
    }
}
