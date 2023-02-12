<?php

namespace App\View\Components\ToDo;

use Illuminate\View\Component;

class Fields extends Component
{
    public $todo;
    public $task;
    public $type;

    public function __construct($todo, $task, $type)
    {
        $this->todo = $todo;
        $this->task = $task;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.todo.fields');
    }
}
