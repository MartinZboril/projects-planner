<?php

namespace App\Services\Data;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NoteService
{
    /**
     * Save data for note.
     */
    public function handleSave(Note $note, array $inputs, Model $model = null): Note
    {
        // Prepare fields
        $inputs['user_id'] = $note->user_id ?? Auth::id();
        $inputs['is_private'] = $inputs['is_private'] ?? false;
        $inputs['is_marked'] = $inputs['is_marked'] ?? false;

        if ($model ?? false) {
            $inputs['noteable_id'] = $model->id;
            $inputs['noteable_type'] = $model::class;
        }

        // Save note
        $note->fill($inputs)->save();

        return $note;
    }

    /**
     * Mark selected note.
     */
    public function handleMark(Note $note): Note
    {
        $note->update(['is_marked' => ! $note->is_marked]);

        return $note->fresh();
    }
}
