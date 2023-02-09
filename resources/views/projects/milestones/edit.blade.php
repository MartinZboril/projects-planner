@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.milestone'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('projects.milestones.update', ['project' => $project, 'milestone' => $milestone]) }}" method="post">
                    @csrf
                    @method('PATCH')
                    @include('projects.milestones.forms.fields', ['project' => $project, 'milestone' => $milestone, 'type' => 'edit'])
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