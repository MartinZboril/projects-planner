<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\File\UploadFileRequest;
use App\Services\{FileService, FlashService};

class UploadFileController extends Controller
{
    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request): RedirectResponse
    {
        try {
            //(new FileService)->handleUploadWithRelations($request->safe(), $request->file('files'));
            (new FlashService)->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->back();
    }
}
