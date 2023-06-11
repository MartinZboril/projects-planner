<?php

namespace App\Http\Controllers\Project\Note;

use Exception;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Note\{StoreNoteRequest, UpdateNoteRequest};
use App\Models\{Project, Note};
use App\Traits\FlashTrait;
use App\Services\Data\{ProjectService, NoteService};

class ProjectNoteController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ProjectService $projectService,
        private NoteService $noteService
    ) {}

    /**
     * Display the notes of project.
     */
    public function index(Project $project): View
    {
        return view('projects.notes.index', ['project' => $project]);
    }

    /**
     * Show the form for creating a new note of project.
     */
    public function create(Project $project): View
    {
        return view('projects.notes.create', ['project' => $project]);
    }

    /**
     * Store a newly created projects note in storage.
     */
    public function store(StoreNoteRequest $request, Project $project): RedirectResponse
    {
        try {
            $this->noteService->handleSave(new Note, $request->validated(), $project);
            $this->flash(__('messages.note.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.notes.index', $project);
    }

    /**
     * Show the form for editing the note of project.
     */
    public function edit(Project $project, Note $note): View
    {
        return view('projects.notes.edit', ['project' => $project, 'note' => $note]);
    }  

    /**
     * Update the note in storage.
     */
    public function update(UpdateNoteRequest $request, Project $project, Note $note): RedirectResponse
    {
        try {
            $this->noteService->handleSave($note, $request->validated(), $project);
            $this->flash(__('messages.note.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.notes.index', $project);
    }
}
