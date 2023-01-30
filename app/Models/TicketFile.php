<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketFile extends Model
{
    use HasFactory;
    
    protected $table = 'tickets_files';

    protected $fillable = [
        'ticket_id',
        'file_id'
    ]; 

    public const VALIDATION_RULES = [
        'ticket_id' => ['required', 'integer', 'exists:tickets,id'],
        'file_id' => ['required', 'integer', 'exists:files,id'],
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
