<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use App\Enums\TicketStatusEnum;
use App\Models\{Comment, Ticket};
use App\Services\FileService;

class TicketService
{
    public function __construct()
    {
    }

    /**
     * Save data for ticket.
     */
    public function handleSave(Ticket $ticket, array $inputs, ?Array $uploadedFiles=[]): Ticket
    {
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
        $ticket->update(['status' => TicketStatusEnum::convert]);
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