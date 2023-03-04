<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId)
    {
    }

    public function render()
    {
        return view('components.user.table');
    }
}

