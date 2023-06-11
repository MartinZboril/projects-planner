<?php

namespace App\Http\Controllers\Note;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Note\{StoreNoteRequest, UpdateNoteRequest};
use App\Models\Note;
use App\Traits\FlashTrait;
use App\Services\Data\NoteService;

class NoteController extends Controller
{
    use FlashTrait;

    public function __construct(
        private NoteService $noteService
    ) {}

    /**
     * Display a listing of the notes.
     */
    public function index(): View
    {
        return view('notes.index', ['notes' => Note::basic()->visible()->orderByDesc('is_marked')->get()]);
    }

    /**
     * Show the form for creating a new note.
     */
    public function create(): View
    {
        return view('notes.create');
    }

    /**
     * Store a newly created note in storage.
     */
    public function store(StoreNoteRequest $request): RedirectResponse
    {
        try {
            $this->noteService->handleSave(new Note, $request->validated());
            $this->flash(__('messages.note.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('notes.index');
    }

    /**
     * Show the form for editing the note.
     */
    public function edit(Note $note): View
    {
        return view('notes.edit', ['note' => $note]);
    }

    /**
     * Update the note in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        try {
            $this->noteService->handleSave($note, $request->validated());
            $this->flash(__('messages.note.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('notes.index');
    }
}