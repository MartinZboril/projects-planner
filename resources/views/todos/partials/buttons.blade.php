@if (!$item->is_finished)
    <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('check-todo-{{ $item->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Finish</a>
    <!-- ToDos forms -->
    @include('todos.forms.check', ['id' => 'check-todo-' . $item->id . '-form', 'todo' => $item, 'action' => true])            
@endif