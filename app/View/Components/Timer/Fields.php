<?php

namespace App\View\Components\Timer;

use App\Models\Project;
use App\Models\Timer;
use Illuminate\View\Component;

class Fields extends Component
{
    public $timer;

    public $type;

    public $closeRoute;

    public function __construct(?Timer $timer, string $type, Project $project)
    {
        $this->timer = $timer;
        $this->type = $type;
        $this->closeRoute = route('projects.timers.index', $project);
    }

    public function render()
    {
        return view('components.timer.fields');
    }
}
