<div class="row">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">{{ $type === 'edit' ? 'Edit' : 'Create' }} Ticket</div>
            <div class="card-body">
                <div class="form-group required">
                    <label for="subject" class="control-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="subject" value="{{ old('subject', $ticket->subject ?? null) }}" >
                    @error('subject')
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
                                <option value="{{ $project->id }}" @selected((int) old('project_id', $ticket->project->id ?? null) === $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif   
                <div class="form-group">
                    <label for="assignee-id">Assignee</label>
                    <select class="form-control @error('assignee_id') is-invalid @enderror" name="assignee_id" id="assignee-id" style="width: 100%;">
                        <option selected value>select assignee</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected((int) old('assignee_id', $ticket->assignee->id ?? null) === $user->id)>{{ $user->full_name }}</option>
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
                            <option value="{{ $key }}" @selected((int) old('type', $ticket->type->value ?? null) === $key)>{{ __('pages.content.tickets.types.' . $value) }}</option>
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
                            <option value="{{ $key }}" @selected((int) old('priority', $ticket->priority->value ?? null) === $key)>{{ __('pages.content.tickets.priorities.' . $value) }}</option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> 
                <div class="form-group required">
                    <label for="dued_at" class="control-label">Due date</label>
                    <input type="date" name="dued_at" id="dued_at" class="form-control @error('dued_at') is-invalid @enderror" placeholder="due date" value="{{ old('dued_at', ($ticket->dued_at ?? false) ? $ticket->dued_at->format('Y-m-d') : now()->addDays(3)->format('Y-m-d')) }}" >
                    @error('dued_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group required">
                    <label for="message" class="control-label">Message</label>
                    <textarea name="message" class="form-control @error('description') is-invalid @enderror" id="message" cols="30" rows="10" placeholder="message">{{ old('message', $ticket->message ?? null) }}</textarea>
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

            $('#project-id').on('change', function () {
                const projectId = this.value;
                $("#assignee-id").html('');
                $.ajax({
                    url: "{{ route('users.load') }}",
                    type: "POST",
                    data: {
                        project_id: projectId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#assignee-id').html('<option value="">select assignee</option>');
                        $.each(result.users, function (key, value) {
                            $("#assignee-id").append('<option value="' + value
                                .id + '">' + value.fullname + '</option>');
                        });
                    }
                });                
            });            


            $('#message').summernote();
        });
    </script>
@endpush