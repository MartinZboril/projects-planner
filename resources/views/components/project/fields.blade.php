<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Project</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $project->name ?? null) }}" autocomplete="off">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="client-id" class="control-label">Client</label>
                    <select class="form-control @error('client_id') is-invalid @enderror" name="client_id" id="client-id" style="width: 100%;">
                        <option disabled selected value>select client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $project->client->id ?? null) === $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="team" class="control-label">Team</label>
                    <select class="form-control @error('team') is-invalid @enderror" name="team[]" multiple="multiple" id="team" style="width: 100%;">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                            @if(old('team')) 
                                @foreach(old('team') as $formUser) 
                                    @selected($formUser === $user->id)
                                @endforeach
                            @elseif ($project->team ?? false)
                                @foreach($project->team as $projectUser)
                                    @selected($projectUser->id === $user->id)
                                @endforeach 
                            @endif>{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                    @error('team')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>                                
                <div class="form-group required">
                    <label for="start_date" class="control-label">Start date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="start date" value="{{ old('start_date', ($project->start_date ?? false) ? $project->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}" autocomplete="off">
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="due_date" class="control-label">Due date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="due date" value="{{ old('due_date', ($project->due_date ?? false) ? $project->due_date->format('Y-m-d') : now()->addMonths(1)->format('Y-m-d')) }}" autocomplete="off">
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="estimated_hours" class="control-label">Estimated hours</label>
                    <input type="number" name="estimated_hours" id="estimated_hours" class="form-control @error('estimated_hours') is-invalid @enderror" placeholder="estimated hours" value="{{ old('estimated_hours', $project->estimated_hours ?? 0) }}" autocomplete="off">
                    @error('estimated_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="budget" class="control-label">Budget</label>
                    <input type="number" name="budget" id="budget" class="form-control @error('budget') is-invalid @enderror" placeholder="budget" value="{{ old('budget', $project->budget ?? 0) }}" autocomplete="off">
                    @error('budget')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="description" class="control-label">Description</label>
                    <textarea name="description" class="form-control summernote" id="description" cols="30" rows="10" placeholder="description">{{ old('description', $project->description ?? null) }}</textarea>
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
            $('#client-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select client'
            });

            $('#team').select2({
                theme: 'bootstrap4',
                placeholder: 'select member'
            });

            $('#description').summernote();
        });
    </script>
@endpush