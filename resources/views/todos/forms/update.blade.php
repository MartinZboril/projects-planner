<form action="{{ route('todos.update', $todo->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-header">Edit ToDo</div>
                <div class="card-body">
                    <div class="form-group required">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $todo->name) }}" autocomplete="off">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="deadline" class="control-label">Deadline</label>
                        <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" placeholder="deadline" value="{{ old('deadline', $todo->deadline->format('Y-m-d')) }}" autocomplete="off">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_finished" class="custom-control-input @error('is_finished') is-invalid @enderror" id="is-finished" value="1" @checked(old('is_finished', $todo->is_finished))>
                            <label class="custom-control-label" for="is-finished">Finished</label>
                        </div>
                        @error('is_finished')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>                                   
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control summernote @error('description') is-invalid @enderror" id="description" cols="30" rows="10" placeholder="description">{{ old('description', $todo->description) }}</textarea>
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
                    <input type="submit" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ route('tasks.detail', $task->id) }}" class="cancel-btn">Close</a></span>
                </div>
            </div>
        </div>
    </div>                                   
    <input type="hidden" name="redirect" value="{{ $redirect }}">           
</form> 