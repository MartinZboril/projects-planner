<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'due_date'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status_id', 2);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status_id', 3);
    }
}
