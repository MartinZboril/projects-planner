<?php

namespace App\View\Components\Client;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId)
    {
        /*$this->clients->each(function (Client $client) {
            $client->edit_route = route('clients.edit', $client);
            $client->show_route = route('clients.show', $client);
        });*/
    }

    public function render()
    {
        return view('components.client.table');
    }
}
