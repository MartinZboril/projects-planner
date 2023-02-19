<?php

namespace App\View\Components\Milestone\UI;

use Illuminate\View\Component;
use App\Models\Milestone;

class Progress extends Component
{
    public $milestone;

    public function __construct(Milestone $milestone)
    {
        $this->milestone = $milestone;
    }

    public function render()
    {
        return view('components.milestone.ui.progress');
    }
}
