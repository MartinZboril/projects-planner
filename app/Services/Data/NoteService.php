<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;
use App\Models\Note;

class NoteService
{
    /**
     * Save data for note.
     */
    public function handleSave(Note $note, ValidatedInput $inputs, bool $basic = false): Note
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
     * Mark selected note.
     */
    public function handleMark(Note $note): Note
    {
        $note->is_marked = !$note->is_marked;
        $note->save();
        return $note;
    }
}