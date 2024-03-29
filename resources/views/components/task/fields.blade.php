<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Task</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $task->name ?? null) }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>  
                @if ($project)
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    @error('project_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror                
                @else      
                    <div class="form-group required">
                        <label for="project-id" class="control-label">Project</label>
                        <select class="form-control @error('project_id') is-invalid @enderror" name="project_id" id="project-id" style="width: 100%;">
                            <option disabled selected value>select project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected((int) old('project_id', $task->project->id ?? null) === $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="form-group">
                    <label for="milestone-id">Milestone</label>
                    <select class="form-control @error('milestone_id') is-invalid @enderror" name="milestone_id" id="milestone-id" style="width: 100%;">
                        <option disabled selected value>select milestone</option>
                        @if ($milestones ?? false)
                            @foreach($milestones as $milestone)
                                <option value="{{ $milestone->id }}" @selected((int) old('milestone_id', $task->milestone->id ?? null) === $milestone->id)>{{ $milestone->name }}</option>
                            @endforeach                    
                        @endif
                    </select>
                    @error('milestone_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="user-id" class="control-label">User</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" name="user_id" id="user-id" style="width: 100%;">
                        <option disabled selected value>select user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected((int) old('user_id', $task->user->id ?? null) === $user->id)>{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> 
                <div class="form-group required">
                    <label for="started_at" class="control-label">Start date</label>
                    <input type="date" name="started_at" id="started_at" class="form-control @error('started_at') is-invalid @enderror" placeholder="start date" value="{{ old('started_at', ($task->started_at ?? false) ? $task->started_at->format('Y-m-d') : now()->format('Y-m-d')) }}" >
                    @error('started_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="dued_at" class="control-label">Due date</label>
                    <input type="date" name="dued_at" id="dued_at" class="form-control @error('dued_at') is-invalid @enderror" placeholder="due date" value="{{ old('dued_at', ($task->dued_at ?? false) ? $task->dued_at->format('Y-m-d') : now()->addDays(7)->format('Y-m-d')) }}" >
                    @error('dued_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="description" class="control-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" cols="30" rows="10" placeholder="description">{{ old('description', $task->description ?? null) }}</textarea>
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
        @if ($type === 'create')
            <div class="card card-primary card-outline">
                <div class="card-header">Files</div>
                <div class="card-body">
                    <input type="file" name="files[]" multiple class="@error('files'){{ 'is-invalid' }}@enderror"> 
                    @error('files')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>            
        @endif          
        <div class="card">
            <div class="card-body">
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ $closeRoute }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#project-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select project'
            });

            $('#milestone-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select milestone'
            });

            $('#user-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select user'
            });

            $('#description').summernote();

            $('#project-id').on('change', function () {
                const projectId = this.value;
                $("#milestone-id").html('');
                $.ajax({
                    url: "{{ route('milestones.load') }}",
                    type: "POST",
                    data: {
                        project_id: projectId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#milestone-id').html('<option value="">select milestone</option>');
                        $.each(result.milestones, function (key, value) {
                            $("#milestone-id").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
                $("#user-id").html('');
                $.ajax({
                    url: "{{ route('users.load') }}",
                    type: "POST",
                    data: {
                        project_id: projectId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#user-id').html('<option value="">select user</option>');
                        $.each(result.users, function (key, value) {
                            $("#user-id").append('<option value="' + value
                                .id + '">' + value.fullname + '</option>');
                        });
                    }
                });                
            });            
        });
    </script>
@endpush