<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'due_date'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function team()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function newTasks()
    {
        return $this->hasMany(Task::class, 'project_id')->new();
    }

    public function inProgressTasks()
    {
        return $this->hasMany(Task::class, 'project_id')->inProgress();
    }

    public function completedTasks()
    {
        return $this->hasMany(Task::class, 'project_id')->completed();
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'project_id');
    }

    public function timers()
    {
        return $this->hasMany(Timer::class, 'project_id');
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'project_id');
    }

    public function getDeadlineAttribute()
    {
        return $this->due_date->diffInDays(\Carbon\Carbon::now()->format('Y-m-d'));
    }

    public function getRemainingHoursAttribute()
    {
        // Todo
        return $this->estimated_hours;
    }

    public function getUsedBudgetAttribute()
    {
        // Todo
        return $this->budget;
    }
}
