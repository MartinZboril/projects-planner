<?php

namespace App\Http\Controllers\Project\File;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProjectFileDestroyController extends Controller
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Project $project, File $file): JsonResponse
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
