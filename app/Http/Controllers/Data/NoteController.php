<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\{StoreNoteRequest, UpdateNoteRequest};
use App\Models\Note;
use App\Services\FlashService;
use App\Services\Data\NoteService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NoteController extends Controller
{
    protected $noteService;
    protected $flashService;

    public function __construct(NoteService $noteService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->noteService = $noteService;
        $this->flashService = $flashService;
    }

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
        return view('notes.create', ['note' => new Note]);
    }

    /**
     * Store a newly created note in storage.
     */
    public function store(StoreNoteRequest $request): RedirectResponse
    {
        try {
            $this->noteService->save(new Note, $request->safe());
            $this->flashService->flash(__('messages.note.create'), 'info');
            return $this->noteService->setUpRedirect($request->type, $request->parent_id);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
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
            $this->noteService->save($note, $request->safe());
            $this->flashService->flash(__('messages.note.update'), 'info');
            return $this->noteService->setUpRedirect($request->type, $request->parent_id);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
    
    /**
     * Mark selected note.
     */
    public function mark(Note $note): RedirectResponse
    {
        try {
            $note = $this->noteService->mark($note);
            $this->flashService->flash(__('messages.note.' . ($note->is_marked ? 'mark' : 'unmark')), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}