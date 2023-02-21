<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use App\Models\Note;

class NoteService
{
    /**
     * Save data for note.
     */
    public function handleSave(Note $note, array $inputs, bool $basic = false): Note
    {
        // Prepare fields
        $inputs['user_id'] = $note->user_id ?? Auth::id();
        $inputs['is_private'] = $inputs['is_private'] ?? false;
        $inputs['is_marked'] = $inputs['is_marked'] ?? false;
        $inputs['is_basic'] = $basic;
        // Save note
        $note->fill($inputs)->save();
        return $note;
    }

    /**
     * Mark selected note.
     */
    public function handleMark(Note $note): Note
    {
        $note->update(['is_marked' => !$note->is_marked]);
        return $note->fresh();
    }
}