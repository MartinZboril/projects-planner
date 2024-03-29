<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-clipboard-list mr-1"></i>
            Todo List
        </div>
        <div class="card-tools">
            <a href="{{ $createFormRoute }}" class="btn btn-sm btn-primary btn-sm float-right"><i class="fas fa-plus"></i>Add</a>
        </div>
    </div>
    <div class="card-body">
        <x-todo.listing :$todos />
    </div>
</div>
