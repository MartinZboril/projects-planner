<form action="{{ route('todos.store') }}" method="post">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header">Create ToDo</div>
                <div class="card-body">
                    <div class="form-group required">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name') }}" autocomplete="off">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="deadline" class="control-label">Deadline</label>
                        <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" placeholder="deadline" value="{{ old('deadline', date('Y-m-d', strtotime('+7 days'))) }}" autocomplete="off">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control summernote" id="description" cols="30" rows="10" placeholder="description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>     
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">Settings</div>
                <div class="card-body">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <input type="submit" class="btn btn-sm btn-primary" value="Create"> or <a href="{{ $redirect == 'projects' ? route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) : route('tasks.detail', $task->id) }}" class="cancel-btn">Close</a></span>
                </div>
            </div>
        </div>
    </div>                                                     
    <input type="hidden" name="redirect" value="{{ $redirect }}">           
    <input type="hidden" name="task_id" value="{{ $task->id }}">           
</form>