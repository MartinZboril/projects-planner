@extends('layouts.master')

@section('title', __('pages.title.analysis'))

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
        <a href="{{ route('reports.timesheets') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-body">
                    <!-- Content -->
                    <div class="table-responsive">
                        <table id="@if($timers->count() > 0){{ 'timesheets-table' }}@endif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Type</th>
                                    <th>User</th>
                                    <th>Total time (Hours)</th>
                                    <th>Amount</th>
                                    <th>Start</th>
                                    <th>Stop</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($timers as $timer)
                                    <tr>
                                        <td>{{ $timer->project->name }}</td>
                                        <td>{{ $timer->rate->name }}</td>
                                        <td>{{ $timer->user->full_name }}</td>
                                        <td>{{ $timer->until ? $timer->total_time : 'N/A' }}</td>
                                        <td>{{ $timer->until ? $timer->amount : 'N/A' }}</td>
                                        <td>{{ $timer->since->format('d.m.Y H:i') }}</td>
                                        <td>{{ $timer->until ? $timer->until->format('d.m.Y H:i') : 'N/A' }}</td>
                                        <td>{{ $timer->since->format('d.m.Y') }}</td>
                                        <td>
                                            @if($timer->until)
                                                <a href="{{ route('timers.edit', ['project' => $timer->project->id, 'timer' => $timer->id]) }}" class="btn btn-sm btn-dark" href=""><i class="fas fa-pencil-alt"></i></a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No timesheets were found!</td>
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
            $("#timesheets-table").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush