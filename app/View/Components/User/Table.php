<?php

namespace App\View\Components\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\User;

class Table extends Component
{
    public $users;
    public $tableId;

    public function __construct(Collection $users, string $tableId)
    {
        $this->users = $users->each(function (User $user) {
            $user->edit_route = route('users.edit', $user);
            $user->show_route = route('users.show', $user);
        });
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.user.table');
    }
}

