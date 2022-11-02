<div class="row">
    <div class="col-md-5">
        <div class="card card-primary card-outline rounded-0">
            <div class="card-header">{{ $task->name }} <span class="badge badge-{{ $task->is_stopped ? 'danger' : ($task->is_returned ? 'danger' : ($task->status == 1 ? 'info' : ($task->status == 2 ? 'warning' : ($task->status == 3 ? 'success' : 'info')))) }} ml-2" style='font-size:14px;'>@include('tasks.partials.status', ['task' => $task])</span></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Due date</span>
                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-danger">{{ $task->due_date->format('d.m.Y') }}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Start date</span>
                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-success">{{ $task->start_date->format('d.m.Y') }}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Created at</span>
                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-secondary">{{ $task->created_at->format('d.m.Y') }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="d-block">Project: <b>{{ $task->project->name }}</b></span>
                <span class="d-block">Client: <b>{{ $task->project->client->name }}</b></span>
                @if ($task->milestone)
                    <span class="d-block">Milestone: <b>{{ $task->milestone->name }}</b></span>
                @endif
                <span class="d-block">User: <b>{{ $task->user->full_name }}</b></span>
                @if ($task->user->id != $task->author->id)
                    <span class="d-block">Author: <b>{{ $task->author->full_name }}</b></span>
                @endif
                <span class="d-block">Status: <b>@include('tasks.partials.status', ['task' => $task])</b></span>
                <hr>
                {!! $task->description !!}
            </div>
        </div>
    </div>
    <div class="col-md-7">
    <div class="card card-primary card-outline rounded-0">
        <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
            <i class="ion ion-clipboard mr-1"></i>
            To Do List
            </h3>
            <div class="card-tools">
                @if($project)
                    <a href="{{ route('projects.todo.create', ['project' => $project->id, 'task' => $task->id]) }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add</a>
                @else
                    <a href="{{ route('todos.create', $task->id) }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Add</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <ul class="todo-list ui-sortable" data-widget="todo-list">
                @foreach ($task->todos as $todo)
                    <li>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo-{{ $todo->id }}" id="todo-check-{{ $todo->id }}" onclick="event.preventDefault(); document.getElementById('check-todo-{{ $todo->id }}-form').submit();"@checked($todo->is_finished)>
                            <label for="todo-check-{{ $todo->id }}"></label>
                        </div>
                        <span class="text">{{ $todo->name }}</span>
                        <small class="badge badge-danger"><i class="far fa-clock"></i> {{ $todo->deadline->format('d.m.Y') }}</small>
                        @if($todo->description)
                            <small class="ml-1">{{ $todo->description }}</small>
                        @endif
                        <div class="tools">
                            @if($project)
                                <a href="{{ route('projects.todo.edit', ['project' => $project->id, 'task' => $task->id, 'todo' => $todo->id]) }}"><i class="fas fa-edit"></i></a>
                            @else
                                <a href="{{ route('todos.edit', ['task' => $task->id, 'todo' => $todo->id]) }}"><i class="fas fa-edit"></i></a>
                            @endif
                        </div>

                        @include('todos.forms.check', ['id' => 'check-todo-' . $todo->id . '-form', 'todo' => $todo, 'redirect' => $project ? 'projects' : 'tasks', 'action' => $todo->is_finished ? 0 : 1])            
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
        <div class="card card-primary card-outline rounded-0">
            <div class="card-header">Activity Feed</div>
            <div class="card-body">
            </div>
        </div>
    </div>
</div> 