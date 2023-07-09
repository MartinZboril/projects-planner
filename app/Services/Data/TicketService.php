<?php

namespace App\Services\Data;

use App\Enums\TicketStatusEnum;
use App\Events\Ticket\TicketAssigneeChanged;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Ticket\Status\ArchivedTicketNotification;
use App\Notifications\Ticket\Status\ClosedTicketNotification;
use App\Notifications\Ticket\Status\ReopenedTicketNotification;
use App\Notifications\Ticket\TicketConvertedToTaskNotification;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Save data for ticket.
     */
    public function handleSave(Ticket $ticket, array $inputs, ?array $uploadedFiles = []): Ticket
    {
        $oldAssigneeId = $ticket->assignee_id;
        // Prepare fields
        $inputs['status'] = $ticket->status ?? TicketStatusEnum::open;
        $inputs['reporter_id'] = $ticket->reporter_id ?? Auth::id();
        $inputs['assignee_id'] = $inputs['assignee_id'] ?? null;
        // Save ticket
        $ticket->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($ticket, $uploadedFiles);
        }
        // Notify assignee about assigning to the ticket
        if (($ticket->assignee ?? false) && ((int) $oldAssigneeId !== (int) $ticket->assignee_id)) {
            TicketAssigneeChanged::dispatch($ticket, $ticket->assignee, ($oldAssigneeId) ? User::find($oldAssigneeId) : null);
        }

        return $ticket;
    }

    /**
     * Upload tickets files.
     */
    public function handleUploadFiles(Ticket $ticket, array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->fileService->handleUpload($uploadedFile, 'tickets/files', $ticket);
        }
    }

    /**
     * Change working status of the ticket.
     */
    public function handleChange(Ticket $ticket, int $status): Ticket
    {
        $ticket->update(['status' => $status]);

        switch ($ticket->status) {
            case TicketStatusEnum::open:
                $ticket->assignee->notify(new ReopenedTicketNotification($ticket));
                break;

            case TicketStatusEnum::close:
                $ticket->reporter->notify(new ClosedTicketNotification($ticket));
                break;

            case TicketStatusEnum::archive:
                $ticket->assignee->notify(new ArchivedTicketNotification($ticket));
                break;
        }

        return $ticket->fresh();
    }

    /**
     * Save that ticket was converted to task.
     */
    public function handleConvert(Ticket $ticket): void
    {
        $ticket->update(['status' => TicketStatusEnum::convert]);
        $ticket->task->user->notify(new TicketConvertedToTaskNotification($ticket));
    }

    /**
     * Mark selected ticket.
     */
    public function handleMark(Ticket $ticket): Ticket
    {
        $ticket->update(['is_marked' => ! $ticket->is_marked]);

        return $ticket->fresh();
    }

    /**
     * Delete selected ticket.
     */
    public function handleDelete(Ticket $ticket): void
    {
        $ticket->delete();
    }
}
