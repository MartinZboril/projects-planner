<?php

namespace App\Http\Controllers\Project\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Models\Note;
use App\Models\Project;
use App\Services\Data\NoteService;
use App\Services\Data\ProjectService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProjectNoteController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ProjectService $projectService,
        private NoteService $noteService
    ) {
    }

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

    /**
     * Remove the projects note from storage.
     */
    public function destroy(Project $project, Note $note): JsonResponse
    {
        try {
            $this->noteService->handleDelete($note);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.note.delete'),
        ]);
    }
}
