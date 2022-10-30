<form action="{{ route('tasks.store') }}" method="post">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-header">Create task</div>
                <div class="card-body">
                    <div class="form-group required">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name') }}" autocomplete="off">
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
                                    <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
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
                            </select>
                            @error('milestone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="project_id" value="{{ old('project_id', $project->id) }}">
                        <div class="form-group">
                            <label for="milestone-id">Milestone</label>
                            <select class="form-control @error('milestone_id') is-invalid @enderror" name="milestone_id" id="milestone-id" style="width: 100%;">
                                <option disabled selected value>select milestone</option>
                                @foreach($project->milestones as $milestone)
                                    <option value="{{ $milestone->id }}" @selected(old('milestone_id') == $milestone->id)>{{ $milestone->name }}</option>
                                @endforeach
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
                                <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} {{ $user->surname }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> 
                    <div class="form-group required">
                        <label for="start_date" class="control-label">Start date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="start date" value="{{ old('start_date', date('Y-m-d')) }}" autocomplete="off">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="due_date" class="control-label">Due date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="due date" value="{{ old('due_date', date("Y-m-d", strtotime('+ 7 day', strtotime(date('Y-m-d'))))) }}" autocomplete="off">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" class="form-control summernote" id="description" cols="30" rows="10" placeholder="description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>     
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-header">Settings</div>
                <div class="card-body">
                </div>
            </div>
            <div class="card rounded-0">
                <div class="card-body">
                    <input type="submit" name="create" class="btn btn-sm btn-primary mr-1" value="Create"><input type="submit" name="create_and_close" class="btn btn-sm btn-secondary" value="Create and close"> or <a href="{{ route('tasks.index') }}" class="cancel-btn">Close</a></span>
                </div>
            </div>
        </div>
    </div>   
    <input type="hidden" name="redirect" value="{{ $redirect }}">
</form>