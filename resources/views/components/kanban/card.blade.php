<div class="card card-{{ $colour }}">
    <div class="card-header">{{ $label }}</div>
    <div class="card-body">
        <!-- Content -->
        @forelse ($tasks as $task)
            <div class="card card-{{ $colour }} card-outline">
                <div class="card-header">
                    <x-kanban.header :$task />
                </div>
                <div class="card-body">
                    <x-kanban.informations :$task />
                </div>
            </div>
            <x-kanban.forms :$task />
        @empty
            <x-kanban.empty />
        @endforelse
    </div>
</div>