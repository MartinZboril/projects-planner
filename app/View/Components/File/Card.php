<?php

namespace App\View\Components\File;

use App\Models\File;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Card extends Component
{
    public $files;

    public $uploadFormRoute;

    public $displayHeader;

    public function __construct(Collection $files, array $parent, string $uploadFormRoute, string $destroyFormRouteName, ?bool $displayHeader = true)
    {
        $this->files = $files->each(function (File $file) use ($parent, $destroyFormRouteName) {
            $file->destroy_route = route($destroyFormRouteName, $parent + ['file' => $file]);
        });
        $this->uploadFormRoute = $uploadFormRoute;
        $this->displayHeader = $displayHeader;
    }

    public function render()
    {
        return view('components.file.card');
    }
}
