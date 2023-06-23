<?php

namespace App\View\Components\Note;

use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Card extends Component
{
    public $notes;

    public $redirect;

    public function __construct(Collection $notes, string $editFormRouteName, string $destroyFormRouteName, ?array $parent = [], ?string $type = 'note')
    {
        $this->notes = $notes->each(function (Note $note) use ($editFormRouteName, $destroyFormRouteName, $parent) {
            $note->edit_route = route($editFormRouteName, $parent + ['note' => $note]);
            $note->destroy_route = route($destroyFormRouteName, $parent + ['note' => $note]);
        });
        switch ($type) {
            case 'client':
                $this->redirect = route('clients.notes.index', $parent);
                break;

            case 'project':
                $this->redirect = route('projects.notes.index', $parent);
                break;

            default:
                $this->redirect = route('notes.index');
        }
    }

    public function render()
    {
        return view('components.note.card');
    }
}
