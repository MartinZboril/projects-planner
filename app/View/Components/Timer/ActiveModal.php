<?php

namespace App\View\Components\Timer;

use Illuminate\View\Component;

class ActiveModal extends Component
{
    public $timers;
    public $modalId;

    public function __construct($timers, $modalId)
    {
        $this->timers = $timers;
        $this->modalId = $modalId;
    }

    public function render()
    {
        return view('components.timer.active-modal');
    }
}
