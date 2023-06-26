<?php

namespace App\Http\Controllers\Client\File;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\File;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ClientFileDestroyController extends Controller
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Client $client, File $file): JsonResponse
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
