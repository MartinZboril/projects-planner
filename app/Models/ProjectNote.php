<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectNote extends Model
{
    use HasFactory;

    protected $table = 'projects_notes';

    protected $fillable = [
        'project_id',
        'note_id',
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'note_id' => ['required', 'integer', 'exists:notes,id'],
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id');
    }
}
