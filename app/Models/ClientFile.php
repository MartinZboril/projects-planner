<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientFile extends Model
{
    use HasFactory;

    protected $table = 'clients_files';

    protected $guarded = ['id']; 

    public const VALIDATION_RULES = [
        'client_id' => ['required', 'integer', 'exists:clients,id'],
        'file_id' => ['required', 'integer', 'exists:files,id'],
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
