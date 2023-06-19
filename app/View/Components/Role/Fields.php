<?php

namespace App\View\Components\Role;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\View\Component;

class Fields extends Component
{
    public $role;

    public $type;

    public $closeRoute;

    public function __construct(?Role $role, string $type)
    {
        $this->role = $role;
        $this->type = $type;
        $this->closeRoute = route('users.roles.index');
    }

    public function render()
    {
        return view('components.role.fields', ['permissions' => Permission::all()]);
    }
}
