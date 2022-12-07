@switch($type)
    @case('project')
        @include('projects.partials.buttons', ['project' => $item])
        @break
        
    @case('task')
        @include('tasks.partials.buttons', ['task' => $item])
        @break
        
    @case('ticket')
        @include('tickets.partials.buttons', ['ticket' => $item])
        @break

    @case('todo')
        @include('todos.partials.buttons', ['todo' => $item])
        @break

@endswitch