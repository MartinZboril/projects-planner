<?php

namespace App\View\Components\Timer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ActiveModal extends Component
{
    public function __construct(public Collection $timers, public string $modalId)
    {
    }    

    public function render()
    {
        return view('components.timer.active-modal');
    }
}
