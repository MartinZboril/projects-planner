<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectFile extends Model
{
    use HasFactory;
           
    protected $table = 'projects_files';

    protected $fillable = [
        'project_id',
        'file_id'
    ]; 

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'file_id' => ['required', 'integer', 'exists:files,id'],
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
