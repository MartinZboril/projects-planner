<?php

namespace App\View\Components\Timer;

use Illuminate\View\Component;

class Fields extends Component
{
    public $timer;
    public $closeRoute;
    public $type;

    public function __construct($timer, $closeRoute, $type)
    {
        $this->timer = $timer;
        $this->closeRoute = $closeRoute;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.timer.fields');
    }
}
