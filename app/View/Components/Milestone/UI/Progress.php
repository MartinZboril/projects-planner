<?php

namespace App\View\Components\Milestone\UI;

use Illuminate\View\Component;
use App\Models\Milestone;

class Progress extends Component
{
    public function __construct(public Milestone $milestone)
    {
    }

    public function render()
    {
        return view('components.milestone.ui.progress');
    }
}
