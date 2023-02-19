<?php

namespace App\View\Components\Site;

use Illuminate\View\Component;

class FlashMessages extends Component
{
    public $message;
    public $type;

    public function __construct(?string $message, ?string $type)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.site.flash-messages');
    }
}
