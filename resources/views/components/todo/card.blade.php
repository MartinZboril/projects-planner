<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-clipboard-list mr-1"></i>
            ToDo List
        </div>
        <div class="card-tools">
            <a href="{{ $createFormRoute }}" class="btn btn-sm btn-primary btn-sm float-right"><i class="fas fa-plus"></i>Add</a>
        </div>
    </div>
    <div class="card-body">
        <x-todo.listing :$parent :$editFormRouteName :$checkerFormPartial :$todos />            
    </div>
</div>