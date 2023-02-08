<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Http\Requests\File\UploadFileRequest;
use App\Http\Controllers\Controller;
use App\Services\FlashService;
use App\Services\Data\ClientService;

class ClientFileUploaderController extends Controller
{
    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request, Client $client): RedirectResponse
    {
        try {
            (new ClientService)->handleUploadFiles($client, $request->file('files'));
            (new FlashService)->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('clients.files.index', [$client]);
    }
}
