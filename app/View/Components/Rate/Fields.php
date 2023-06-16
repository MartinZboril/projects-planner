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

    public function __construct(?Rate $rate, User $user, string $type)
    {
        $this->rate = $rate;
        $this->type = $type;
        $this->closeRoute = route('users.show', $user);
    }

    public function render()
    {
        return view('components.rate.fields');
    }
}
