<?php

namespace App\View\Components\Client;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Client;

class Table extends Component
{
    public $clients;
    public $tableId;

    public function __construct(Collection $clients, string $tableId)
    {
        $this->clients = $clients->each(function (Client $client) {
            $client->edit_route = route('clients.edit', $client);
            $client->show_route = route('clients.show', $client);
        });
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.client.table');
    }
}
