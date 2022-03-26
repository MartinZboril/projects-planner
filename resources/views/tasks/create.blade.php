@extends('layouts.master')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('tasks.store') }}" method="post">
                @csrf
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
                                <div class="form-group required">
                                    <label for="project_id" class="control-label">Project</label>
                                    <select class="form-control project-select @error('project_id') is-invalid @enderror" name="project_id" id="project_id" style="width: 100%;">
                                        <option disabled selected value>Choose project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" @if(old('project_id') == $project->id) selected @endif>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group required">
                                    <label for="user_id" class="control-label">User</label>
                                    <select class="form-control user-select @error('user_id') is-invalid @enderror" name="user_id" id="user_id" style="width: 100%;">
                                        <option disabled selected value>Choose user</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if(old('user_id') == $user->id) selected @endif>{{ $user->name }} {{ $user->surname }}</option>
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
                                    <span data-validate-for="description" class="text-danger binline modal-validate-for"></span>
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

            $('.user-select').select2({
                theme: 'bootstrap4',
                placeholder: 'select user'
            });

            $('#description').summernote();
        });
    </script>
@endsection