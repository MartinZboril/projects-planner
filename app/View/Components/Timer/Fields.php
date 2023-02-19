<?php

namespace App\View\Components\Timer;

use Illuminate\View\Component;
use App\Models\{Project, Timer};

class Fields extends Component
{
    public $closeRoute;

    public function __construct(public ?Timer $timer, public string $type, Project $project)
    {
        $this->closeRoute = route('projects.timers.index', $project);    
    }

    public function render()
    {
        return view('components.timer.fields');
    }
}
