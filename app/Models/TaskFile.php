<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskFile extends Model
{
    use HasFactory;
        
    protected $table = 'tasks_files';

    protected $fillable = [
        'task_id',
        'file_id'
    ]; 

    public const VALIDATION_RULES = [
        'task_id' => ['required', 'integer', 'exists:tasks,id'],
        'file_id' => ['required', 'integer', 'exists:files,id'],
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
