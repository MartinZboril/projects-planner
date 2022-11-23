@switch($type)
    @case('project')
        <i class="fas fa-clock mr-2"></i>
        @break
        
    @case('task')
        <i class="fas fa-tasks mr-2"></i>
        @break
        
    @case('ticket')
        <i class="fas fa-life-ring mr-2"></i>
        @break

    @case('todo')
        <i class="fas fa-check-square mr-2"></i>
        @break

    @default
        <i class="far fa-list mr-2"></i>
@endswitch