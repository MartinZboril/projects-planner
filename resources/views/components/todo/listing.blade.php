<ul class="todo-list" data-widget="todo-list">
    @foreach ($todos as $todo)
        <li id="todo-item-{{ $todo->id }}">
            <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo-{{ $todo->id }}" id="todo-check-{{ $todo->id }}" class="todo-check-button" onclick="checkTodo('{{ $todo->check_route }}')" @checked($todo->is_finished)>
                <label for="todo-check-{{ $todo->id }}"></label>
            </div>
            <span class="text">{{ $todo->name }}</span>
            <small class="badge badge-{{ $todo->overdue ? 'danger' : 'secondary' }}" id="todo-{{ $todo->id }}-due-date"><i class="far fa-clock mr-1"></i>{{ $todo->due_date->format('d.m.Y') }}</small>
            @if($todo->description)
                <small class="ml-1">{{ $todo->description }}</small>
            @endif
            <div class="tools">
                <a href="{{ $todo->edit_route ?? route('tasks.todos.edit', ['task' => $todo->task, 'todo' => $todo]) }}"><i class="fas fa-edit"></i></a>
                <a href="#" onclick="destroyTodo('{{ $todo->destroy_route }}', 'card')" class="text-danger"><i class="fas fa-trash-alt"></i></a>
            </div>
        </li>
    @endforeach
</ul>