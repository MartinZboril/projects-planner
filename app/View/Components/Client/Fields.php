<?php

namespace App\View\Components\Client;

use Illuminate\View\Component;

class Fields extends Component
{
    public $client;
    public $type;

    public function __construct($client, $type)
    {
        $this->client = $client;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.client.fields');
    }
}
