<?php

namespace App\View\Components\Note;

use Illuminate\View\Component;
use App\Models\Note;

class Fields extends Component
{
    public $note;
    public $type;
    public $closeRoute;

    public function __construct(?Note $note, string $type, string $closeRoute)
    {
        $this->note = $note;
        $this->type = $type;
        $this->closeRoute = $closeRoute;
    }

    public function render()
    {
        return view('components.note.fields');
    }
}
