<?php

namespace App\Http\Controllers\Note;

use Exception;
use Illuminate\Http\{JsonResponse, Request};
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
    public function __invoke(Note $note, Request $request): JsonResponse
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
