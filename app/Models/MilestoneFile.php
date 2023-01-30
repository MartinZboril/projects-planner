<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MilestoneFile extends Model
{
    use HasFactory;
        
    protected $table = 'milestones_files';

    protected $fillable = [
        'milestone_id',
        'file_id'
    ]; 

    public const VALIDATION_RULES = [
        'milestone_id' => ['required', 'integer', 'exists:milestones,id'],
        'file_id' => ['required', 'integer', 'exists:files,id'],
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
