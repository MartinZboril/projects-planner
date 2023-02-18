<?php

namespace App\View\Components\Milestone;

use Illuminate\View\Component;

class Fields extends Component
{
    public $milestone;
    public $project;
    public $type;

    public function __construct($milestone, $project, $type)
    {
        $this->milestone = $milestone;
        $this->project = $project;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.milestone.fields');
    }
}
