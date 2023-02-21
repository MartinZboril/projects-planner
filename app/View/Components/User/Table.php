<?php

namespace App\View\Components\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\User;

class Table extends Component
{
    public function __construct(public Collection $users, public string $tableId)
    {
        $this->users->each(function (User $user) {
            $user->edit_route = route('users.edit', $user);
            $user->show_route = route('users.show', $user);
        });
    }

    public function render()
    {
        return view('components.user.table');
    }
}

