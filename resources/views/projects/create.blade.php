@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('projects.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-primary card-outline">
                                <div class="card-header">Create project</div>
                                <div class="card-body">
                                    <div class="form-group required">
                                        <label for="name" class="control-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name') }}" autocomplete="off">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group required">
                                        <label for="client-id" class="control-label">Client</label>
                                        <select class="form-control @error('client_id') is-invalid @enderror" name="client_id" id="client-id" style="width: 100%;">
                                            <option disabled selected value>select client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>{{ $client->name }}</option>
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
                                                    @foreach(old('team') as $formUser) @selected($formUser == $user->id) @endforeach
                                                @endif>{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('team')
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
                                        <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="due date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" autocomplete="off">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group required">
                                        <label for="estimated_hours" class="control-label">Estimated hours</label>
                                        <input type="number" name="estimated_hours" id="estimated_hours" class="form-control @error('estimated_hours') is-invalid @enderror" placeholder="estimated hours" value="{{ old('estimated_hours', 0) }}" autocomplete="off">
                                        @error('estimated_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group required">
                                        <label for="budget" class="control-label">Budget</label>
                                        <input type="number" name="budget" id="budget" class="form-control @error('budget') is-invalid @enderror" placeholder="budget" value="{{ old('budget', 0) }}" autocomplete="off">
                                        @error('budget')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group required">
                                        <label for="description" class="control-label">Description</label>
                                        <textarea name="description" class="form-control summernote" id="description" cols="30" rows="10" placeholder="description">{{ old('description') }}</textarea>
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
                                    <input type="submit" name="create" class="btn btn-sm btn-primary mr-1" value="Create"><input type="submit" name="create_and_close" class="btn btn-sm btn-secondary" value="Create and close"> or <a href="{{ route('projects.index') }}" class="cancel-btn">Close</a></span>
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>     
            </div>
        </section>
    </div>
@endsection

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