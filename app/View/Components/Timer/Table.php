<?php

namespace App\View\Components\Timer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Timer;

class Table extends Component
{
    public function __construct(public Collection $timers, public string $tableId, public string $type='timers')
    {
        $this->timers->each(function (Timer $timer) {
            $timer->edit_route = $timer->until ? route('projects.timers.edit', ['project' => $timer->project, 'timer' => $timer]) : null;
        });
    }

    public function render()
    {
        return view('components.timer.table');
    }
}
