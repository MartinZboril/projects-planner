<?php

namespace App\Services\Data;

use App\Enums\Routes\{ClientRouteEnum, NoteRouteEnum, ProjectRouteEnum};
use App\Models\{Client, ClientNote, Note, Project, ProjectNote};
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\Auth;

class NoteService
{
    /**
     * Store new note.
     */
    public function store(ValidatedInput $inputs): Note
    {
        $note = new Note;
        $note->user_id = Auth::id();
        $note->type = $inputs->type;

        $note = $this->save($note, $inputs);

        if($parentId = $inputs->parent_id) {
            $this->saveRelation($note, $parentId, $inputs->type);
        }

        return $note;
    }

    /**
     * Update note.
     */
    public function update(Note $note, ValidatedInput $inputs): Note
    {
        return $this->save($note, $inputs);
    }

    /**
     * Save data for note.
     */
    protected function save(Note $note, ValidatedInput $inputs): Note
    {
        $note->name = $inputs->name;
        $note->is_private = $inputs->has('is_private');
        $note->is_marked = $inputs->has('is_marked');
        $note->content = $inputs->content;
        $note->save();

        return $note;
    }

    protected function saveRelation(Note $note, int $parentId, string $type): Note
    {
        if($type == 'project') {
            $projectNote = new ProjectNote;
            $projectNote->project_id = $parentId;
            $projectNote->note_id = $note->id;
            $projectNote->save();    
        } elseif($type == 'client') {
            $clientNote = new ClientNote;
            $clientNote->client_id = $parentId;
            $clientNote->note_id = $note->id;
            $clientNote->save(); 
        }

        $note->is_basic = false;
        $note->save();

        return $note;
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
     * Set up redirect for the action
     */
    public function setUpRedirect(Note $note, $type, $parentId): RedirectResponse
    {
        switch ($type) {
            case 'project':
                $redirectAction = ProjectRouteEnum::Notes;
                $redirectVars = ['project' => Project::find($parentId)];
                break;                
                
            case 'client':
                $redirectAction = ClientRouteEnum::Notes;
                $redirectVars = ['client' => Client::find($parentId)];
                break;  

            default:
                return redirect()->route(NoteRouteEnum::Index->value);
                break;
        }
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}