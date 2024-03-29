<?php

namespace App\View\Components\Note;

use App\Models\Note;
use Illuminate\View\Component;

class Fields extends Component
{
    public function __construct(public ?Note $note, public string $type, public string $closeRoute)
    {
    }

    public function render()
    {
        return view('components.note.fields');
    }
}
