@extends('layouts.master')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.tasks', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('tasks.update', $task->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-7">
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header">Edit task</div>
                            <div class="card-body">
                                <div class="form-group required">
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $task->name) }}" autocomplete="off">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="milestone_id">Milestone</label>
                                    <select class="form-control milestone-select @error('milestone_id') is-invalid @enderror" name="milestone_id" id="milestone_id" style="width: 100%;">
                                        <option disabled selected value>Choose project</option>
                                        @if($task->project)
                                            @foreach($task->project->milestones as $milestone)
                                                <option value="{{ $milestone->id }}" @if(old('milestone_id', $task->milestone_id) == $milestone->id) selected @endif>{{ $milestone->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('milestone_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group required">
                                    <label for="user_id" class="control-label">User</label>
                                    <select class="form-control user-select @error('user_id') is-invalid @enderror" name="user_id" id="user_id" style="width: 100%;">
                                        <option disabled selected value>Choose user</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if(old('user_id', $task->user_id) == $user->id) selected @endif>{{ $user->name }} {{ $user->surname }}</option>
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
                        <div class="card card-primary card-outline rounded-0">
                            <div class="card-header">Settings</div>
                            <div class="card-body">
                            </div>
                        </div>
                        <div class="card rounded-0">
                            <div class="card-body">
                                <input type="submit" name="project_save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="project_save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ route('projects.tasks', $project->id) }}" class="cancel-btn">Close</a></span>
                            </div>
                        </div>
                    </div>
                </div>    

                <input type="hidden" name="project_id" value="{{ $project->id }}">
            </form>     
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#project_id').change(function() {
                var url = '{{ url('projects') }}/' + $(this).val() + '/milestones/get/';

                $.get(url, function(data) {
                    var milestoneSelect = $('#milestone_id');
                    milestoneSelect.empty();

                    $.each(data,function(key, value) {
                        milestoneSelect.append('<option disabled selected value>Choose milestone</option>');
                        milestoneSelect.append('<option value=' + value.id + '>' + value.name + '</option>');
                    });
                });
            });

            $('.project-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select project'
            });

            $('.milestone-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select milestone'
            });

            $('.user-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select user'
            });

            $('#description').summernote();
        });
    </script>
@endsection