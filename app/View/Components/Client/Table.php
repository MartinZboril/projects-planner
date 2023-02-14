<?php

namespace App\View\Components\Client;

use Illuminate\View\Component;

class Table extends Component
{
    public $clients;
    public $tableId;

    public function __construct($clients, $tableId)
    {
        $this->clients = $clients;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.client.table');
    }
}
