<?php

namespace App\View\Components\Todo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\ToDo;

class Card extends Component
{
    public $todos;
    public $createFormRoute;
    public $checkerFormPartial;

    public function __construct(array $parent, Collection $todos, string $createFormRoute, string $editFormRouteName, string $checkerFormPartial)
    {
        $this->todos = $todos->each(function (ToDo $todo) use($parent, $editFormRouteName) {
            $todo->edit_route = route($editFormRouteName, $parent + ['todo' => $todo]);
        });        
        $this->createFormRoute = $createFormRoute;
        $this->checkerFormPartial = $checkerFormPartial;
    }

    public function render()
    {
        return view('components.todo.card');
    }
}
