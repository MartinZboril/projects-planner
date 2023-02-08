<?php

namespace App\Services\Data;

use Exception;
use App\Services\RouteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use App\Models\{Client, ClientNote, Note, Project, ProjectNote};
use App\Enums\Routes\{ClientRouteEnum, NoteRouteEnum, ProjectRouteEnum};

class NoteService
{
    /**
     * Save data for note.
     */
    public function save(Note $note, ValidatedInput $inputs, bool $basic = false): Note
    {
        return Note::updateOrCreate(
            ['id' => $note->id],
            [
                'user_id' => $note->user_id ?? Auth::id(),
                'name' => $inputs->name,
                'content' => $inputs->content,
                'is_private' => $inputs->is_private ?? false,
                'is_basic' => $basic,
            ]
        ); 
    }

    /**
     * Save relation for note.
     */
    protected function saveRelation(Note $note, int $parentId, string $parentType): void
    {
        switch ($parentType) {
            case 'project':
                ProjectNote::create(['project_id' => $parentId, 'note_id' => $note->id]);
                break;
            default:
                throw new Exception('For the sent type was not found relationship to save!');
                break;
        }
    }

    /**
     * Mark selected note.
     */
    public function mark(Note $note): Note
    {
        $note->is_marked = !$note->is_marked;
        $note->save();
        return $note;
    }

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect($type, $parentId): RedirectResponse
    {
        switch ($type) {
            case 'project':
                $redirectAction = ProjectRouteEnum::Notes;
                $redirectVars = ['project' => Project::find($parentId)];
                break;                 

            default:
                return redirect()->route(NoteRouteEnum::Index->value);
                break;
        }
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}