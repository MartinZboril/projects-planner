<?php

namespace App\View\Components\User;

use App\Models\User;
use Illuminate\View\Component;

class Fields extends Component
{
    public $user;

    public $type;

    public $closeRoute;

    public function __construct(?User $user, string $type)
    {
        $this->user = $user;
        $this->type = $type;
        $this->closeRoute = $this->getCloseRoute($user, $type);
    }

    private function getCloseRoute(?User $user, string $type): string
    {
        return $type === 'edit'
                    ? route('users.show', $user)
                    : route('users.index');
    }

    public function render()
    {
        return view('components.user.fields');
    }
}
