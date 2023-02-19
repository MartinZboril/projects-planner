<?php

namespace App\View\Components\Todo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Listing extends Component
{
    public $todos;
    public $checkerFormPartial;

    public function __construct(Collection $todos, string $checkerFormPartial)
    {
        $this->todos = $todos;
        $this->checkerFormPartial = $checkerFormPartial;
    }

    public function render()
    {
        return view('components.todo.listing');
    }
}
