<?php

namespace App\View\Components\Note;

use Illuminate\View\Component;

class Fields extends Component
{
    public $note;
    public $type;

    public function __construct($note, $type)
    {
        $this->note = $note;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.note.fields');
    }
}
