<div class="card card-{{ $colour }}">
    <div class="card-header">{{ $label }}</div>
    <div class="card-body">
        <!-- Content -->
        @forelse ($tasks as $task)
            <div class="card card-{{ $colour }} card-outline">
                <div class="card-header">
                    @include('projects.tasks.kanban.header', ['task' => $task])
                </div>
                <div class="card-body">
                    @include('projects.tasks.kanban.information', ['task' => $task])
                </div>
            </div>
            @include('projects.tasks.kanban.forms', ['task' => $task])
        @empty
            @include('projects.tasks.kanban.empty')
        @endforelse
    </div>
</div>