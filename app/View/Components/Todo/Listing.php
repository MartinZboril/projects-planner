<?php

namespace App\View\Components\Todo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Listing extends Component
{
    public function __construct(public Collection $todos)
    {
    }

    public function render()
    {
        return view('components.todo.listing');
    }
}
