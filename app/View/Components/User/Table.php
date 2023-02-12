<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class Table extends Component
{
    public $users;
    public $tableId;

    public function __construct($users, $tableId)
    {
        $this->users = $users;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.user.table');
    }
}

