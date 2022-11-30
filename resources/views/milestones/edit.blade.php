@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.milestone'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('milestones.detail', ['project' => $project->id, 'milestone' => $milestone->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('milestones.update', $milestone->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-primary card-outline">
                                <div class="card-header">Edit milestone</div>
                                <div class="card-body">
                                    <div class="form-group required">
                                        <label for="name" class="control-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ old('name', $milestone->name) }}" autocomplete="off">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group required">
                                        <label for="owner-id" class="control-label">Owner</label>
                                        <select class="form-control @error('owner_id') is-invalid @enderror" name="owner_id" id="owner-id" style="width: 100%;">
                                            <option disabled selected value>select owner</option>
                                            @foreach($project->team as $user)
                                                <option value="{{ $user->id }}" @selected(old('owner_id', $milestone->owner_id) == $user->id)>{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('owner_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> 
                                    <div class="form-group required">
                                        <label for="start_date" class="control-label">Start date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="start date" value="{{ old('start_date', $milestone->start_date->format('Y-m-d')) }}" autocomplete="off">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group required">
                                        <label for="end_date" class="control-label">End date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" placeholder="due date" value="{{ old('end_date', $milestone->end_date->format('Y-m-d')) }}" autocomplete="off">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group required">
                                        <label for="colour" class="control-label">Colour</label>
                                        <input type="color" name="colour" id="colour" class="form-control @error('colour') is-invalid @enderror" placeholder="colour" value="{{ old('colour', $milestone->colour) }}" autocomplete="off">
                                        @error('colour')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" class="form-control summernote" id="description" cols="30" rows="10" placeholder="description">{{ old('description', $milestone->description) }}</textarea>
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
                                    <input type="submit" name="save" class="btn btn-sm btn-primary mr-1" value="Save"><input type="submit" name="save_and_close" class="btn btn-sm btn-secondary" value="Save and close"> or <a href="{{ route('milestones.detail', ['project' => $project->id, 'milestone' => $milestone->id]) }}" class="cancel-btn">Close</a></span>
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
            $('#owner-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select owner'
            });

            $('#description').summernote();
        });
    </script>
@endpush