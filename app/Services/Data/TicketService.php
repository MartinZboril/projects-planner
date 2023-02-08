<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;
use App\Enums\TicketStatusEnum;
use App\Models\{Comment, Ticket, TicketComment, TicketFile};
use App\Services\FileService;

class TicketService
{
    public function __construct(private ProjectUserService $projectUserService)
    {
    }

    /**
     * Save data for ticket.
     */
    public function handleSave(Ticket $ticket, ValidatedInput $inputs): Ticket
    {
        $ticket = Ticket::updateOrCreate(
            ['id' => $ticket->id],
            [
                'status' => $ticket->status ?? TicketStatusEnum::open,
                'reporter_id' => $ticket->reporter_id ?? Auth::id(),
                'project_id' => $inputs->project_id,
                'assignee_id' => $inputs->assignee_id ?? null,
                'subject' => $inputs->subject,
                'type' => $inputs->type,
                'priority' => $inputs->priority,
                'due_date' => $inputs->due_date,
                'message' => $inputs->message,
            ]
        );

        $this->projectUserService->storeUser($ticket->project, $ticket->reporter);
        
        if($ticket->assignee_id) {
            $this->projectUserService->storeUser($ticket->project, $ticket->assignee);
        }

        return $ticket;
    }

    /**
     * Upload tickets files.
     */
    public function handleUploadFiles(Ticket $ticket, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            TicketFile::create([
                'ticket_id' => $ticket->id,
                'file_id' => (new FileService)->upload($uploadedFile, 'tasks/files')->id
            ]);
        }
    }

    /**
     * Save tickets comments.
     */
    public function handleSaveComment(Ticket $ticket, Comment $comment): void
    {
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'comment_id' => $comment->id
        ]);
    }

    /**
     * Change working status of the ticket.
     */
    public function handleChange(Ticket $ticket, int $status): Ticket
    {
        $ticket->status = $status;
        $ticket->save();
        return $ticket;
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
        $ticket->is_marked = !$ticket->is_marked;
        $ticket->save();
        return $ticket;
    }
}