@switch($type)
    @case('project')
        @if ($item->status == 1)
            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('finish-project-{{ $item->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Finish</a>
        @elseif ($item->status == 2 || $item->status == 3)
            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('active-project-{{ $item->id }}-form').submit();"><i class="fas fa-cogs mr-1"></i>Active</a>
        @endif
        @if ($item->status != 2 && $item->status != 3)
            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-project-{{ $item->id }}-form').submit();"><i class="fas fa-archive"></i></a>
        @endif
        <!-- Projects forms -->
        @include('projects.partials.forms', ['project' => $item])
        @break
        
    @case('task')
        @if ($item->status == 1)
            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $item->id }}-form').submit();"><i class="fas fa-play mr-1"></i>Start</a>
        @elseif ($item->status == 2)
            <a href="#" class="btn btn-sm btn-success {{ ($item->is_stopped) ? 'disabled' : '' }}" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $item->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Complete</a>
            @if ($item->is_stopped)
                <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $item->id }}-form').submit();"><i class="fas fa-hourglass-start mr-1"></i>Resume</a>
            @else
                <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $item->id }}-form').submit();"><i class="fas fa-stop mr-1"></i>Stop</a>
            @endif
        @else
            <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $item->id }}-form').submit();"><i class="fas fa-undo mr-1"></i>Return</a>
        @endif
        <!-- Tasks forms -->
        @include('tasks.partials.forms', ['task' => $item])
        @break
        
    @case('ticket')
        @if (!$item->is_convert && $item->assignee_id && $item->status != 2 && $item->status != 3)
            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('convert-ticket-{{ $item->id }}-to-task-form').submit();"><i class="fas fa-tasks mr-1"></i>Convert to task</a>
        @endif
        @if ($item->status == 1)
            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('close-ticket-{{ $item->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Close</a>
        @elseif ($item->status == 2 || $item->status == 3)
            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('open-ticket-{{ $item->id }}-form').submit();"><i class="fas fa-bell mr-1"></i>Open</a>
        @endif
        @if ($item->status != 2 && $item->status != 3)
            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-ticket-{{ $item->id }}-form').submit();"><i class="fas fa-archive"></i></a>
        @endif
        <!-- Tickets forms -->
        @include('tickets.partials.forms', ['ticket' => $item])                
        @break

    @case('todo')
        @if (!$item->is_finished)
            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('check-todo-{{ $item->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Finish</a>
            <!-- ToDos forms -->
            @include('todos.forms.check', ['id' => 'check-todo-' . $item->id . '-form', 'todo' => $item, 'action' => true])            
        @endif
        @break

@endswitch