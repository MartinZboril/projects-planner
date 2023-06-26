<?php

namespace App\Http\Controllers\Client\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Models\Client;
use App\Models\Note;
use App\Services\Data\ClientService;
use App\Services\Data\NoteService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ClientNoteController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ClientService $clientService,
        private NoteService $noteService
    ) {
    }

    /**
     * Display the notes of client.
     */
    public function index(Client $client): View
    {
        return view('clients.notes.index', ['client' => $client]);
    }

    /**
     * Show the form for creating a new note of client.
     */
    public function create(Client $client): View
    {
        return view('clients.notes.create', ['client' => $client]);
    }

    /**
     * Store a newly created clients note in storage.
     */
    public function store(StoreNoteRequest $request, Client $client): RedirectResponse
    {
        try {
            $this->noteService->handleSave(new Note, $request->validated(), $client);
            $this->flash(__('messages.note.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('clients.notes.index', $client);
    }

    /**
     * Show the form for editing the note of client.
     */
    public function edit(Client $client, Note $note): View
    {
        return view('clients.notes.edit', ['client' => $client, 'note' => $note]);
    }

    /**
     * Update the note in storage.
     */
    public function update(UpdateNoteRequest $request, Client $client, Note $note): RedirectResponse
    {
        try {
            $this->noteService->handleSave($note, $request->validated(), $client);
            $this->flash(__('messages.note.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('clients.notes.index', $client);
    }

    /**
     * Remove the clients note from storage.
     */
    public function destroy(Client $client, Note $note): JsonResponse
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
