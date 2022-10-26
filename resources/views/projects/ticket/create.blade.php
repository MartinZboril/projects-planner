@extends('layouts.master')

@section('title', __('pages.title.project'))

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
            @include('tickets.forms.store', ['type' => 'project', 'project' => $project, 'redirect' => 'projects'])    
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