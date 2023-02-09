@switch($type)
    @case('client')
        @include('clients.partials.buttons', ['client' => $item, 'buttonSize' => 'xs', 'buttonText' => true])
        @break

    @case('milestone')
        @include('projects.milestones.partials.buttons', ['milestone' => $item, 'buttonSize' => 'xs', 'buttonText' => true])
        @break

    @case('project')
        @include('projects.partials.buttons', ['project' => $item, 'buttonSize' => 'xs', 'buttonText' => false])
        @break
        
    @case('task')
        @include('tasks.partials.buttons', ['task' => $item, 'buttonSize' => 'xs', 'buttonText' => false])
        @break
        
    @case('ticket')
        @include('tickets.partials.buttons', ['ticket' => $item, 'buttonSize' => 'xs', 'buttonText' => false, 'redirect' => 'tickets'])
        @break

    @case('todo')
        @include('todos.partials.buttons', ['todo' => $item, 'buttonSize' => 'xs', 'buttonText' => false])
        @break

@endswitch