<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientNote extends Model
{
    use HasFactory;

    protected $table = 'clients_notes';

    protected $guarded = ['id']; 

    public const VALIDATION_RULES = [
        'client_id' => ['required', 'integer', 'exists:clients,id'],
        'note_id' => ['required', 'integer', 'exists:notes,id'],
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id');
    }
}
