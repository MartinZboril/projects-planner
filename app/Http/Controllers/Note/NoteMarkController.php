<?php

namespace App\Http\Controllers\Note;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Traits\FlashTrait;
use App\Services\Data\NoteService;

class NoteMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private NoteService $noteService)
    {  
    }

    /**
     * Mark selected note.
     */
    public function __invoke(Note $note): RedirectResponse
    {
        try {
            $note = $this->noteService->handleMark($note);
            $this->flash(__('messages.note.' . ($note->is_marked ? 'mark' : 'unmark')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('notes.index');
    }
}
