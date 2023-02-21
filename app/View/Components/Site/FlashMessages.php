<?php

namespace App\View\Components\Site;

use Illuminate\View\Component;

class FlashMessages extends Component
{
    public function __construct(public ?string $message, public ?string $type)
    {
    }

    public function render()
    {
        return view('components.site.flash-messages');
    }
}
