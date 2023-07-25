<?php

namespace App\View\Components\Milestone\Ui;

use App\Models\Milestone;
use Illuminate\View\Component;

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
