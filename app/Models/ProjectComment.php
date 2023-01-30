<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectComment extends Model
{
    use HasFactory;
    
    protected $table = 'projects_comments';

    protected $fillable = [
        'project_id',
        'comment_id'
    ]; 
    
    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'comment_id' => ['required', 'integer', 'exists:comments,id'],
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
