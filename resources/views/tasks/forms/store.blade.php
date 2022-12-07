<form action="{{ route('tasks.store') }}" method="post">
    @csrf
    @method('POST')
    @include('tasks.forms.fields', ['task' => $task, 'project' => $project, 'type' => 'create'])
    <input type="hidden" name="redirect" value="{{ $redirect }}">
</form>