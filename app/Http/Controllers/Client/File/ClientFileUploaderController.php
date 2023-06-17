<?php

namespace App\Http\Controllers\Client\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\UploadFileRequest;
use App\Models\Client;
use App\Services\Data\ClientService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ClientFileUploaderController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ClientService $clientService
    ) {
    }

    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request, Client $client): RedirectResponse
    {
        try {
            $this->clientService->handleUploadFiles($client, $request->file('files'));
            $this->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('clients.files.index', $client);
    }
}
