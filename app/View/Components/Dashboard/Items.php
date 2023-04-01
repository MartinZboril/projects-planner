<?php

namespace App\View\Components\Dashboard;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Items extends Component
{
    public function __construct(public Collection $items, public string $title)
    {
    }

    public function render()
    {
        return view('components.dashboard.items');
    }
}
