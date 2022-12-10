@switch($type)
    @case('project')
        @include('projects.partials.buttons', ['project' => $item, 'buttonSize' => 'xs', 'buttonText' => false])
        @break
        
    @case('task')
        @include('tasks.partials.buttons', ['task' => $item, 'buttonSize' => 'xs', 'buttonText' => false])
        @break
        
    @case('ticket')
        @include('tickets.partials.buttons', ['ticket' => $item, 'buttonSize' => 'xs', 'buttonText' => false])
        @break

    @case('todo')
        @include('todos.partials.buttons', ['todo' => $item, 'buttonSize' => 'xs', 'buttonText' => false])
        @break

@endswitch