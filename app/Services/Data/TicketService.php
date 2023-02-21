<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use App\Enums\TicketStatusEnum;
use App\Models\{Comment, Ticket};
use App\Services\FileService;

class TicketService
{
    public function __construct(private ProjectUserService $projectUserService)
    {
    }

    /**
     * Save data for ticket.
     */
    public function handleSave(Ticket $ticket, array $inputs): Ticket
    {
        // Prepare fields
        $inputs['status'] = $ticket->status ?? TicketStatusEnum::open;
        $inputs['reporter_id'] = $ticket->reporter_id ?? Auth::id();
        $inputs['assignee_id'] = $inputs['assignee_id'] ?? null;
        // Save ticket
        $ticket->fill($inputs)->save();
        // Store tickets projects users
        $this->projectUserService->handleStoreUser($ticket->project, $ticket->reporter);
        if($ticket->assignee_id) {
            $this->projectUserService->handleStoreUser($ticket->project, $ticket->assignee);
        }

        return $ticket;
    }

    /**
     * Upload tickets files.
     */
    public function handleUploadFiles(Ticket $ticket, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $ticket->files()->save((new FileService)->handleUpload($uploadedFile, 'tasks/files'));
        }
    }

    /**
     * Save tickets comments.
     */
    public function handleSaveComment(Ticket $ticket, Comment $comment): void
    {
        $ticket->comments()->save($comment);
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
        $ticket->update(['status' => TicketStatusEnum::archive, 'is_convert' => true]);
    }

    /**
     * Mark selected ticket.
     */
    public function handleMark(Ticket $ticket): Ticket
    {
        $ticket->update(['is_marked' => !$ticket->is_marked]);
        return $ticket->fresh();
    }
}