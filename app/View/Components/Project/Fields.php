<?php

namespace App\View\Components\Project;

use Illuminate\View\Component;
use App\Models\{Client, User};

class Fields extends Component
{
    public $project;
    public $type;

    public function __construct($project, $type)
    {
        $this->project = $project;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.project.fields', ['clients' => Client::all(), 'users' => User::all()]);
    }
}
