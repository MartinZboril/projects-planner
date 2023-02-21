<?php

namespace App\View\Components\Rate;

use Illuminate\View\Component;
use App\Models\{Rate, User};

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
