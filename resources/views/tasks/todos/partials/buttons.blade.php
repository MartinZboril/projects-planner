@if (!$item->is_finished)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-success" onclick="event.preventDefault(); document.getElementById('check-todo-{{ $item->id }}-form').submit();"><x-site.ui.icon icon="fas fa-check" :text="$hideButtonText ?? 'Finish'" /></a>
    <!-- ToDos forms -->
    @include('tasks.todos.forms.check', ['id' => 'check-todo-' . $item->id . '-form', 'todo' => $item, 'action' => true])            
@endif