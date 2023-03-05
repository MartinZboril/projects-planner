<?php

namespace App\View\Components\Client;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId)
    {
    }

    public function render()
    {
        return view('components.client.table');
    }
}
