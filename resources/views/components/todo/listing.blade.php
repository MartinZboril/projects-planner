<ul class="todo-list ui-sortable" data-widget="todo-list">
    @foreach ($todos as $todo)
        <li>
            <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo-{{ $todo->id }}" id="todo-check-{{ $todo->id }}" onclick="event.preventDefault(); document.getElementById('check-todo-{{ $todo->id }}-form').submit();"@checked($todo->is_finished)>
                <label for="todo-check-{{ $todo->id }}"></label>
            </div>
            <span class="text">{{ $todo->name }}</span>
            <small class="badge badge-{{ $todo->overdue ? 'danger' : 'secondary' }}"><i class="far fa-clock mr-1"></i>{{ $todo->due_date->format('d.m.Y') }}</small>
            @if($todo->description)
                <small class="ml-1">{{ $todo->description }}</small>
            @endif
            <div class="tools">
                <a href="{{ $todo->edit_route }}"><i class="fas fa-edit"></i></a>
            </div>
            <!-- ToDos forms -->
            @include($checkerFormPartial, ['id' => 'check-todo-' . $todo->id . '-form', 'todo' => $todo, 'action' => $todo->is_finished ? 0 : 1])            
        </li>
    @endforeach
</ul>