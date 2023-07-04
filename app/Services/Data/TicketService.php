<?php

namespace App\Services\Data;

use App\Models\User;
use App\Models\Ticket;
use App\Services\FileService;
use App\Enums\TicketStatusEnum;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Ticket\AssigneeAssignedNotification;
use App\Notifications\Ticket\AssigneeUnassignedNotification;

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
            $ticket->assignee->notify(new AssigneeAssignedNotification($ticket));
            
            if ($oldAssigneeId) {
                User::find($oldAssigneeId)->notify(new AssigneeUnassignedNotification($ticket));
            }
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

        return $ticket->fresh();
    }

    /**
     * Save that ticket was converted to task.
     */
    public function handleConvert(Ticket $ticket): void
    {
        $ticket->update(['status' => TicketStatusEnum::convert]);
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
