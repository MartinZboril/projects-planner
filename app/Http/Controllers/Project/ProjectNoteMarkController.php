<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Traits\FlashTrait;
use App\Models\{Project, Note};
use App\Services\Data\NoteService;

class ProjectNoteMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private NoteService $noteService)
    {  
    }

    /**
     * Mark selected note.
     */
    public function __invoke(Project $project, Note $note): RedirectResponse
    {
        try {
            $note = $this->noteService->handleMark($note);
            $this->flash(__('messages.note.' . ($note->is_marked ? 'mark' : 'unmark')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.notes.index', $project);
    }
}
