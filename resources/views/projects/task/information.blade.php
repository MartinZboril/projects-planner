<span class="d-block">Due date: <span class="btn btn-sm btn-outline-danger disabled mb-1" style="font-size:14px;">{{ $task->due_date->format('d.m.Y') }}</span></span>
<span class="d-block">User: <b>{{ $task->user->full_name }}</b></span>
<span class="d-block">Author: <b>{{ $task->author->full_name }}</b></span>
<span class="d-block">Milestone: <b>{{ $task->milestone_label }}</b></span>