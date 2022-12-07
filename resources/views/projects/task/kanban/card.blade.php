<div class="card card-{{ $colour }}">
    <div class="card-header">{{ $label }}</div>
    <div class="card-body">
        <!-- Content -->
        @forelse ($tasks as $task)
            <div class="card card-{{ $colour }} card-outline">
                <div class="card-header">
                    @include('projects.task.kanban.header', ['task' => $task])
                </div>
                <div class="card-body">
                    @include('projects.task.kanban.information', ['task' => $task])
                </div>
            </div>
            @include('projects.task.kanban.forms', ['task' => $task])
        @empty
            @include('projects.task.kanban.empty')
        @endforelse
    </div>
</div>