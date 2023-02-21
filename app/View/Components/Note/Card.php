<?php

namespace App\View\Components\Note;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Note;

class Card extends Component
{
    public $notes;

    public function __construct(Collection $notes, string $editFormRouteName, ?array $parent=[])
    {
        $this->notes = $notes->each(function (Note $note) use($editFormRouteName, $parent) {
            $note->edit_route = route($editFormRouteName, $parent + ['note' => $note]);
        });
    }

    public function render()
    {
        return view('components.note.card');
    }
}
