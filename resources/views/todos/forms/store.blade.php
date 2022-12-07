<form action="{{ route('todos.store') }}" method="post">
    @csrf
    @method('POST')
    @include('todos.forms.fields', ['todo' => $todo, 'task' => $task, 'type' => 'create'])                                                                         
    <input type="hidden" name="redirect" value="{{ $redirect }}">           
    <input type="hidden" name="task_id" value="{{ $task->id }}">           
</form>