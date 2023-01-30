<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MilestoneComment extends Model
{
    use HasFactory;
    
    protected $table = 'milestones_comments';

    protected $guarded = ['id']; 

    public const VALIDATION_RULES = [
        'milestone_id' => ['required', 'integer', 'exists:milestones,id'],
        'comment_id' => ['required', 'integer', 'exists:comments,id'],
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
