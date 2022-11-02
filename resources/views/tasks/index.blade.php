@extends('layouts.master')

@section('title', __('pages.title.task'))

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-body">
                    <!-- Message -->
                    @include('site.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                    <!-- Content -->
                    <div class="table-responsive">
                        <table id="@if(count($tasks) > 0){{ 'tasks-table' }}@endif" class="table table-bordered table-striped">
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
                                        <td><a href="{{ route('tasks.detail', $task->id) }}">{{ $task->name }}</a></td>
                                        <td>{{ $task->project->name }}</td>
                                        <td><img class="img-circle" src="{{ asset('dist/img/user.png') }}" alt="User Image" style="width:35px;height:35px;" data-toggle="tooltip" title="{{ $task->user->name }} {{ $task->user->surname }}"></td>
                                        <td>{{ $task->due_date->format('d.m.Y') }}</td>
                                        <td>@include('tasks.partials.status', ['task' => $task])</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No tasks were found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>  
                    </div>
                </div>
            </div>            
        </div>
    </section>
</div>
@endsection

@push('scripts')
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
    <!-- Toastr -->
    <script src="{{ asset('js/toastr.js') }}"></script>
    <!-- Custom -->
    <script>
        $(function () {
            $("#tasks-table").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush