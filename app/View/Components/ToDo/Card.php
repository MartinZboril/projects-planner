<?php

namespace App\View\Components\Todo;

use Illuminate\View\Component;

class Card extends Component
{
    public $parent;
    public $todos;
    public $createFormRoute;
    public $editFormRouteName;
    public $checkerFormPartial;

    public function __construct($parent, $todos, $createFormRoute, $editFormRouteName, $checkerFormPartial)
    {
        $this->parent = $parent;
        $this->todos = $todos;
        $this->createFormRoute = $createFormRoute;
        $this->editFormRouteName = $editFormRouteName;
        $this->checkerFormPartial = $checkerFormPartial;
    }

    public function render()
    {
        return view('components.todo.card');
    }
}
