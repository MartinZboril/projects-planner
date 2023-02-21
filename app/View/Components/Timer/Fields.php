<?php

namespace App\View\Components\Timer;

use Illuminate\View\Component;
use App\Models\{Project, Timer};

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
