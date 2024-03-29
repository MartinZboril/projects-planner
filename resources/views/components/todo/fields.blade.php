<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Todo</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $todo->name ?? null) }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="dued_at" class="control-label">Deadline</label>
                    <input type="date" name="dued_at" id="dued_at" class="form-control @error('dued_at') is-invalid @enderror" placeholder="due date" value="{{ old('dued_at', ($todo->dued_at ?? false) ? $todo->dued_at->format('Y-m-d') : now()->addDays(3)->format('Y-m-d')) }}" >
                    @error('dued_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_finished" class="custom-control-input @error('is_finished') is-invalid @enderror" id="is-finished" value="1" @checked(old('is_finished', $todo->is_finished ?? null))>
                        <label class="custom-control-label" for="is-finished">Finished</label>
                    </div>
                    @error('is_finished')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control summernote @error('description') is-invalid @enderror" id="description" cols="30" rows="10" placeholder="description">{{ old('description', $todo->description ?? null) }}</textarea>
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
                <input type="submit" class="btn btn-sm btn-primary mr-1" value="Save"> or <a href="{{ $closeRoute }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>
