<?php

namespace App\View\Components\Client;

use Illuminate\View\Component;
use App\Models\Client;

class Fields extends Component
{
    public $client;
    public $type;
    public $closeRoute;

    public function __construct(?Client $client, string $type)
    {
        $this->client = $client;
        $this->type = $type;
        $this->closeRoute = $this->getCloseRoute($client, $type);
    }

    private function getCloseRoute(?Client $client, string $type): string
    {
        return $type === 'edit'
                    ? route('clients.show', $client)
                    : route('clients.index');
    }

    public function render()
    {
        return view('components.client.fields');
    }
}
