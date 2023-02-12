<?php

namespace App\View\Components\Todo;

use Illuminate\View\Component;

class Listing extends Component
{
    public $todos;
    public $editFormRouteName;
    public $checkerFormPartial;

    public function __construct($todos, $editFormRouteName, $checkerFormPartial)
    {
        $this->todos = $todos;
        $this->editFormRouteName = $editFormRouteName;
        $this->checkerFormPartial = $checkerFormPartial;
    }

    public function render()
    {
        return view('components.todo.listing');
    }
}
