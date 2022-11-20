<form action="{{ route('tasks.update', $task->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header">Edit task</div>
                <div class="card-body">
                    <div class="form-group required">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $task->name) }}" autocomplete="off">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if($type == 'task')            
                        <div class="form-group required">
                            <label for="project-id" class="control-label">Project</label>
                            <select class="form-control @error('project_id') is-invalid @enderror" name="project_id" id="project-id" style="width: 100%;">
                                <option disabled selected value>select project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" @selected(old('project_id', $task->project_id) == $project->id)>{{ $project->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="milestone-id">Milestone</label>
                            <select class="form-control @error('milestone_id') is-invalid @enderror" name="milestone_id" id="milestone-id" style="width: 100%;">
                                <option disabled selected value>select milestone</option>
                                @if($task->project)
                                    @foreach($task->project->milestones as $milestone)
                                        <option value="{{ $milestone->id }}" @selected(old('milestone_id', $task->milestone_id) == $milestone->id)>{{ $milestone->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('milestone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="form-group">
                            <label for="milestone-id">Milestone</label>
                            <select class="form-control @error('milestone_id') is-invalid @enderror" name="milestone_id" id="milestone-id" style="width: 100%;">
                                <option disabled selected value>select milestone</option>
                                @if($task->project)
                                    @foreach($task->project->milestones as $milestone)
                                        <option value="{{ $milestone->id }}" @selected(old('milestone_id', $task->milestone_id) == $milestone->id)>{{ $milestone->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('milestone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="form-group required">
                        <label for="user-id" class="control-label">User</label>
                        <select class="form-control @error('user_id') is-invalid @enderror" name="user_id" id="user-id" style="width: 100%;">
                            <option disabled selected value>select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id', $task->user_id) == $user->id)>{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> 
                    <div class="form-group required">
                        <label for="start_date" class="control-label">Start date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="start date" value="{{ old('start_date', $task->start_date->format('Y-m-d')) }}" autocomplete="off">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="due_date" class="control-label">Due date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="due date" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}" autocomplete="off">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" class="form-control summernote" id="description" cols="30" rows="10" placeholder="description">{{ old('description', $task->description) }}</textarea>
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
                    <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ $redirect == 'projects' ? route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) : route('tasks.index') }}" class="cancel-btn">Close</a></span>
                </div>
            </div>
        </div>
    </div>   
    <input type="hidden" name="redirect" value="{{ $redirect }}"> 
</form> 