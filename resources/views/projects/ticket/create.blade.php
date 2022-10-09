@extends('layouts.master')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.tickets', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('projects.ticket.store', $project->id) }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-7">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header">Create ticket</div>
                            <div class="card-body">
                                <div class="form-group required">
                                    <label for="subject" class="control-label">Subject</label>
                                    <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="subject" value="{{ old('subject') }}" autocomplete="off">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="assignee_id">Assignee</label>
                                    <select class="form-control assignee-select @error('assignee_id') is-invalid @enderror" name="assignee_id" id="assignee_id" style="width: 100%;">
                                        <option disabled selected value>Choose assignee</option>
                                        @foreach($project->team as $user)
                                            <option value="{{ $user->id }}" @if(old('assignee_id') == $user->id) selected @endif>{{ $user->name }} {{ $user->surname }}</option>
                                        @endforeach
                                    </select>
                                    @error('assignee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> 
                                <div class="form-group required">
                                    <label for="type" class="control-label">Type</label>
                                    <select class="form-control type-select @error('type') is-invalid @enderror" name="type" id="type" style="width: 100%;">
                                        <option disabled selected value>Choose type</option>
                                        <option value="1" @if(old('type') == '1') selected @endif>Error</option>
                                        <option value="2" @if(old('type') == '2') selected @endif>Inovation</option>
                                        <option value="3" @if(old('type') == '3') selected @endif>Help</option>
                                        <option value="4" @if(old('type') == '4') selected @endif>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group required">
                                    <label for="priority" class="control-label">Priority</label>
                                    <select class="form-control priority-select @error('priority') is-invalid @enderror" name="priority" id="priority" style="width: 100%;">
                                        <option disabled selected value>Choose priority</option>
                                        <option value="1" @if(old('priority') == '1') selected @endif>Low</option>
                                        <option value="2" @if(old('priority') == '2') selected @endif>Medium</option>
                                        <option value="3" @if(old('priority') == '3') selected @endif>High</option>
                                        <option value="4" @if(old('priority') == '4') selected @endif>Urgent</option>
                                    </select>
                                    @error('priority')
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
                                    <label for="message" class="control-label">Message</label>
                                    <textarea name="message" class="form-control summernote" id="message" cols="30" rows="10" placeholder="message">{{ old('message') }}</textarea>
                                    @error('message')
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
                                <input type="submit" name="create" class="btn btn-sm btn-primary mr-1" value="Create"><input type="submit" name="create_and_close" class="btn btn-sm btn-secondary" value="Create and close"> or <a href="{{ route('projects.tickets', $project->id) }}" class="cancel-btn">Close</a></span>
                            </div>
                        </div>
                    </div>
                </div>    
            </form>     
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.project-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select project'
            });

            $('.milestone-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select milestone'
            });

            $('.assignee-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select assignee',
                allowClear: true
            });

            $('.type-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select type'
            });

            $('.priority-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select priority'
            });

            $('#message').summernote();
        });
    </script>
@endsection