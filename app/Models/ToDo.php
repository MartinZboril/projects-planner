<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;

    protected $table = 'todos';

    protected $dates = ['deadline'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
