@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                @include('tasks.forms.update', ['type' => 'project', 'task' => $task, 'redirect' => 'projects'])         
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#project-id').change(function() {
                var url = '{{ url('projects') }}/' + $(this).val() + '/milestones/get/';

                $.get(url, function(data) {
                    var milestoneSelect = $('#milestone-id');
                    milestoneSelect.empty();

                    $.each(data,function(key, value) {
                        milestoneSelect.append('<option disabled selected value>Choose milestone</option>');
                        milestoneSelect.append('<option value=' + value.id + '>' + value.name + '</option>');
                    });
                });
            });

            $('#project-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select project'
            });

            $('#milestone-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select milestone'
            });

            $('#user-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select user'
            });

            $('#description').summernote();
        });
    </script>
@endpush