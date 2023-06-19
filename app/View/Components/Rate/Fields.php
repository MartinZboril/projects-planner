<?php

namespace App\View\Components\Rate;

use App\Models\Rate;
use App\Models\User;
use Illuminate\View\Component;

class Fields extends Component
{
    public $rate;

    public $type;

    public $closeRoute;

    public function __construct(?Rate $rate, string $type)
    {
        $this->rate = $rate;
        $this->type = $type;
        $this->closeRoute = route('users.rates.index');
    }

    public function render()
    {
        return view('components.rate.fields', ['users' => User::all()]);
    }
}
