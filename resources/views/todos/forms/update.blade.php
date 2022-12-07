<form action="{{ route('todos.update', $todo->id) }}" method="post">
    @csrf
    @method('PATCH')
    @include('todos.forms.fields', ['todo' => $todo, 'task' => $task, 'type' => 'edit'])                                                                         
    <input type="hidden" name="redirect" value="{{ $redirect }}">           
</form> 