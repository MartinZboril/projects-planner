<?php

namespace App\View\Components\Milestone\UI;

use Illuminate\View\Component;

class Progress extends Component
{
    public $milestone;

    public function __construct($milestone)
    {
        $this->milestone = $milestone;
    }

    public function render()
    {
        return view('components.milestone.ui.progress');
    }
}
