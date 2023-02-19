<?php

namespace App\View\Components\File;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Card extends Component
{
    public $files;
    public $uploadFormRoute;
    public $displayHeader;

    public function __construct(Collection $files, string $uploadFormRoute, ?bool $displayHeader=true)
    {
        $this->files = $files;
        $this->uploadFormRoute = $uploadFormRoute;
        $this->displayHeader = $displayHeader;
    }

    public function render()
    {
        return view('components.file.card');
    }
}
