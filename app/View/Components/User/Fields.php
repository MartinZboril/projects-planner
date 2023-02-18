<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class Fields extends Component
{
    public $user;
    public $type;

    public function __construct($user, $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.user.fields');
    }
}
