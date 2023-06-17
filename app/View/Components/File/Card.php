<?php

namespace App\View\Components\File;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(public Collection $files, public string $uploadFormRoute, public ?bool $displayHeader = true)
    {
    }

    public function render()
    {
        return view('components.file.card');
    }
}
