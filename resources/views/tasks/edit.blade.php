@extends('layouts.master')

@section('title', __('pages.title.task'))

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tasks.detail', $task->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('tasks.forms.update', ['type' => 'task', 'task' => $task, 'projects' => $projects, 'redirect' => 'tasks'])         
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

@push('scripts')
    <!-- Custom -->
    <script>
        $(document).ready(function() {
            $('#project-id').change(function() {
                var url = '{{ url('milestones') }}/load/?project_id=' + $(this).val();

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