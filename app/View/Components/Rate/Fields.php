<?php

namespace App\View\Components\Rate;

use Illuminate\View\Component;

class Fields extends Component
{
    public $rate;
    public $user;
    public $type;

    public function __construct($rate, $user, $type)
    {
        $this->rate = $rate;
        $this->user = $user;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.rate.fields');
    }
}
