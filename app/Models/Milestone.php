<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'end_date'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function tasksCompleted()
    {
        return $this->hasMany(Task::class, 'milestone_id')->completed();
    }

    public function getProgressAttribute()
    {
        return (count($this->tasks) > 0) ? count($this->tasksCompleted) / count($this->tasks) : 0;
    }
}
