<?php

namespace App\Http\Controllers\Note;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Note\MarkNoteRequest;
use App\Models\Note;
use App\Services\Data\NoteService;

class NoteMarkController extends Controller
{
    public function __construct(
        private NoteService $noteService
    ) {}

    /**
     * Mark selected note.
     */
    public function __invoke(Note $note, MarkNoteRequest $request): JsonResponse
    {
        try {
            $note = $this->noteService->handleMark($note);
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return response()->json([
            'redirect' => $request->redirect,
        ]);
    }
}
