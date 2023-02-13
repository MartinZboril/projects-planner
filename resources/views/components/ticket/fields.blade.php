<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'create' ? 'Edit' : 'Create' }}ticket</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="subject" class="control-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="subject" value="{{ old('subject', $ticket->subject ?? null) }}" autocomplete="off">
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>           
                <div class="form-group required">
                    <label for="project-id" class="control-label">Project</label>
                    <select class="form-control @error('project_id') is-invalid @enderror" name="project_id" id="project-id" style="width: 100%;">
                        <option selected value>select project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id', $ticket->project->id ?? null) === $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="assignee-id">Assignee</label>
                    <select class="form-control @error('assignee_id') is-invalid @enderror" name="assignee_id" id="assignee-id" style="width: 100%;">
                        <option selected value>select assignee</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('assignee_id', $ticket->assignee->id ?? null) === $user->id)>{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                    @error('assignee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> 
                <div class="form-group required">
                    <label for="type" class="control-label">Type</label>
                    <select class="form-control @error('type') is-invalid @enderror" name="type" id="type" style="width: 100%;">
                        <option selected value>select type</option>
                        @foreach(App\Enums\TicketTypeEnum::values() as $key => $value)
                            <option value="{{ $key }}" @selected(old('type', $ticket->type->value ?? null) === $key)>{{ __('pages.content.tickets.types.' . $value) }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="priority" class="control-label">Priority</label>
                    <select class="form-control @error('priority') is-invalid @enderror" name="priority" id="priority" style="width: 100%;">
                        <option selected value>select priority</option>
                        @foreach(App\Enums\TicketPriorityEnum::values() as $key => $value)
                            <option value="{{ $key }}" @selected(old('priority', $ticket->priority->value ?? null) === $key)>{{ __('pages.content.tickets.priorities.' . $value) }}</option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> 
                <div class="form-group required">
                    <label for="due_date" class="control-label">Due date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="due date" value="{{ old('due_date', isset($ticket->due_date) ? $ticket->due_date->format('Y-m-d') : null) }}" autocomplete="off">
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="message" class="control-label">Message</label>
                    <textarea name="message" class="form-control summernote" id="message" cols="30" rows="10" placeholder="message">{{ old('message', $ticket->message ?? null) }}</textarea>
                    @error('message')
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
                <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ $type === 'edit' ? route('tickets.show', $ticket) : route('tickets.index') }}" class="cancel-btn">Close</a></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#project-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select project'
            });

            $('#milestone-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select milestone'
            });

            $('#assignee-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select assignee',
            });

            $('#status').select2({
                theme: 'bootstrap4',
                placeholder: 'select status'
            });

            $('#type').select2({
                theme: 'bootstrap4',
                placeholder: 'select type'
            });

            $('#priority').select2({
                theme: 'bootstrap4',
                placeholder: 'select priority'
            });

            $('#message').summernote();
        });
    </script>
@endpush