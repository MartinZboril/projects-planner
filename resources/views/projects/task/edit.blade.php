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
            @include('tasks.forms.update', ['type' => 'project', 'task' => $task, 'redirect' => 'tasks'])         
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