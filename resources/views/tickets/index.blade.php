@extends('layouts.master')

@section('title', __('pages.title.ticket'))

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
        <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-body">
                    <!-- Message -->
                    @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                    <!-- Content -->
                    <div class="table-responsive">
                        <table id="@if(count($tickets) > 0){{ 'tickets-table' }}@endif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Project</th>
                                    <th>Reporter</th>
                                    <th>Assignee</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Priority</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    <tr>
                                        <td><a href="{{ route('tickets.detail', $ticket->id) }}">{{ $ticket->subject }}</a></td>
                                        <td>{{ $ticket->project->name }}</td>
                                        <td>@include('site.partials.user', ['user' => $ticket->reporter])</td>
                                        <td>
                                            @if($ticket->assignee)                                            
                                                @include('site.partials.user', ['user' => $ticket->assignee])
                                            @else
                                                NaN
                                            @endif
                                        </td>
                                        <td>{{ $ticket->created_at->format('d.m.Y') }}</td>
                                        <td>@include('tickets.partials.status', ['status' => $ticket->status])</td>
                                        <td>@include('tickets.partials.type', ['type' => $ticket->type])</td>
                                        <td>@include('tickets.partials.priority', ['priority' => $ticket->priority])</td>
                                        <td>{{ $ticket->due_date->format('d.m.Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No tickets were found!</td>
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
            $("#tickets-table").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush