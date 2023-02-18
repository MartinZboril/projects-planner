<?php

namespace App\Http\Controllers\Project\Ticket;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\UploadFileRequest;
use App\Models\{Project, Ticket};
use App\Traits\FlashTrait;
use App\Services\Data\TicketService;

class ProjectTicketFileUploaderController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService)
    {
    }

    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request, Project $project, Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->handleUploadFiles($ticket, $request->file('files'));
            $this->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    }
}
