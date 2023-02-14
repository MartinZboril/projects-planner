@switch($type)
    @case('client')
        @include('clients.partials.buttons', ['client' => $item, 'buttonSize' => 'xs', 'buttonText' => true])
        @break

    @case('milestone')
        @include('projects.milestones.partials.buttons', ['milestone' => $item, 'buttonSize' => 'xs', 'buttonText' => true])
        @break

    @case('project')
        @include('projects.partials.buttons', ['project' => $item, 'buttonSize' => 'xs', 'hideButtonText' => ''])
        @break
        
    @case('task')
        @include('tasks.partials.buttons', ['task' => $item, 'buttonSize' => 'xs', 'hideButtonText' => ''])
        @break
        
    @case('ticket')
        @include('tickets.partials.buttons', ['ticket' => $item, 'buttonSize' => 'xs', 'hideButtonText' => ''])
        @break

    @case('todo')
        @include('tasks.todos.partials.buttons', ['todo' => $item, 'buttonSize' => 'xs', 'hideButtonText' => ''])
        @break

@endswitch