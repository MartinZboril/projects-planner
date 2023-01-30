<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\FlashService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\FileService;
use App\Http\Requests\File\UploadFileRequest;

class FileController extends Controller
{
    protected $fileService;
    protected $flashService;

    public function __construct(FileService $fileService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->fileService = $fileService;
        $this->flashService = $flashService;
    }

    /**
     * Upload a newly created file in storage.
     */
    public function upload(UploadFileRequest $request)
    {
        try {
            $this->fileService->uploadWithRelations($request->safe(), $request->file('files'));
            $this->flashService->flash(__('messages.file.upload'), 'info');

            return $this->fileService->setUpRedirect($request->type, $request->parent_id);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
