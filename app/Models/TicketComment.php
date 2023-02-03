<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketComment extends Model
{
    use HasFactory;
            
    protected $table = 'tickets_comments';

    protected $fillable = [
        'ticket_id', 'comment_id',
    ]; 

    public const VALIDATION_RULES = [
        'ticket_id' => ['required', 'integer', 'exists:tickets,id'],
        'comment_id' => ['required', 'integer', 'exists:comments,id'],
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
