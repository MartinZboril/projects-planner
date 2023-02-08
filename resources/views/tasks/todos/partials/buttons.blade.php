@if (!$item->is_finished)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-success" onclick="event.preventDefault(); document.getElementById('check-todo-{{ $item->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-check', 'text' => $buttonText ? 'Finish' : ''])</a>
    <!-- ToDos forms -->
    @include('todos.forms.check', ['id' => 'check-todo-' . $item->id . '-form', 'todo' => $item, 'action' => true])            
@endif