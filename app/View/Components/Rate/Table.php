<?php

namespace App\View\Components\Rate;

use Illuminate\View\Component;
use App\Models\User;

class Table extends Component
{
    public function __construct(public User $user, public string $tableId)
    {
    }

    public function render()
    {
        return view('components.rate.table');
    }
}
