<?php

namespace App\View\Components\Client;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Client;

class Table extends Component
{
    public function __construct(public Collection $clients, public string $tableId)
    {
        $this->clients->each(function (Client $client) {
            $client->edit_route = route('clients.edit', $client);
            $client->show_route = route('clients.show', $client);
        });
    }

    public function render()
    {
        return view('components.client.table');
    }
}
