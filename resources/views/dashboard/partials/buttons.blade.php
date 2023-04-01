@switch($type)
    @case('client')
        @include('clients.partials.buttons', ['client' => $item, 'buttonSize' => 'xs', 'tableIdentifier' => ''])
        @break

    @case('milestone')
        @include('projects.milestones.partials.buttons', ['milestone' => $item, 'buttonSize' => 'xs', 'tableIdentifier' => ''])
        @break

    @case('project')
        @include('projects.partials.buttons', ['project' => $item, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table', 'tableIdentifier' => ''])
        @break
        
    @case('task')
        @include('tasks.partials.buttons', ['task' => $item, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table', 'tableIdentifier' => ''])
        @break
        
    @case('ticket')
        @include('tickets.partials.buttons', ['ticket' => $item, 'buttonSize' => 'xs', 'hideButtonText' => '', 'type' => 'table', 'tableIdentifier' => ''])
        @break

    @case('todo')
        @include('tasks.todos.partials.buttons', ['todo' => $item, 'buttonSize' => 'xs', 'hideButtonText' => '', 'tableIdentifier' => ''])
        @break

@endswitch