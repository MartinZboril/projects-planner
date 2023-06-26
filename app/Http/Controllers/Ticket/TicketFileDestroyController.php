<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Ticket;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TicketFileDestroyController extends Controller
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Ticket $ticket, File $file): JsonResponse
    {
        try {
            $this->fileService->handleDelete($file);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.file.delete'),
        ]);
    }
}
