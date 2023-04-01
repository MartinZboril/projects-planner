<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Milestone</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $milestone->name ?? null) }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="owner-id" class="control-label">Owner</label>
                    <select class="form-control @error('owner_id') is-invalid @enderror" name="owner_id" id="owner-id" style="width: 100%;">
                        <option disabled selected value>select owner</option>
                        @foreach($project->team as $user)
                            <option value="{{ $user->id }}" @selected(old('owner_id', $milestone->owner_id ?? null) === $user->id)>{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                    @error('owner_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> 
                <div class="form-group required">
                    <label for="start_date" class="control-label">Start date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="start date" value="{{ old('start_date', ($milestone->start_date ?? false) ? $milestone->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}" >
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="due_date" class="control-label">End date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="due date" value="{{ old('due_date', ($milestone->due_date ?? false) ? $milestone->due_date->format('Y-m-d') : now()->addDays(7)->format('Y-m-d')) }}" >
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="colour" class="control-label">Colour</label>
                    <input type="color" name="colour" id="colour" class="form-control @error('colour') is-invalid @enderror" placeholder="colour" value="{{ old('colour', $milestone->colour ?? null) }}" >
                    @error('colour')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" cols="30" rows="10" placeholder="description">{{ old('description', $milestone->description ?? null) }}</textarea>
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
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ $closeRoute }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#owner-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select owner'
            });

            $('#description').summernote();
        });
    </script>
@endpush