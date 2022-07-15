@extends('layouts.master')

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-body">
                    <input type="hidden" id="taskform-message" value="{{ Session::get('message') }}">
                    <input type="hidden" id="taskform-message-type" value="{{ Session::get('type') }}">

                    <div class="table-responsive">
                        <table id="{{ count($tasks) > 0 ? 'tasks-table' : '' }}" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Project</th>
                                    <th>User</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tasks as $task)
                                    <tr>
                                        <td><a href="{{ route('tasks.detail', $task->id) }}">{{ $task->name }} {{ $task->surname }}</a></td>
                                        <td>{{ $task->project->name }}</td>
                                        <td><img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="User Image" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $task->user->name }} {{ $task->user->surname }}"></td>
                                        <td>{{ $task->due_date->format('d.m.Y') }}</td>
                                        <td>{!! $task->is_stopped ? 'Stopped' : $task->status->name !!}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No tasks were found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>  
                    </div>
                </div>
            </div>            
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(function () {
            $("#tasks-table").DataTable();

            if($('#taskform-message').val()) {
                if($('#taskform-message-type').val() == "success") {
                    toastr.success($('#taskform-message').val());
                } else if($('#taskform-message-type').val() == "info") {
                    toastr.info($('#taskform-message').val());
                } else {
                    toastr.error($('#taskform-message').val());            
                }
            }; 

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection