<form action="{{ route('tasks.update', $task->id) }}" method="post">
    @csrf
    @method('PATCH')
    @include('tasks.forms.fields', ['task' => $task, 'project' => $project, 'type' => 'edit'])
    <input type="hidden" name="redirect" value="{{ $redirect }}"> 
</form> 