@extends('layouts.master')

@section('title', __('pages.title.dashboard'))

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 mb-3" style="background-color:white;">
        @include('dashboard.partials.action', ['active' => 'index'])
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            @include('dashboard.partials.widgets', ['text' => 'Today', 'value' => $data->get('today_timers_total_time_sum') . ' Hours', 'icon' => 'fas fa-business-time', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
            @include('dashboard.partials.widgets', ['text' => 'Today', 'value' => $data->get('today_timers_amount_sum'), 'icon' => 'fas fa-coins', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
            @include('dashboard.partials.widgets', ['text' => 'NaN', 'value' => 'NaN', 'icon' => 'fas fa-times', 'colour' => 'danger color-palette', 'link' => null])
            @include('dashboard.partials.widgets', ['text' => 'NaN', 'value' => 'NaN', 'icon' => 'fas fa-times', 'colour' => 'danger color-palette', 'link' => null])
            @include('dashboard.partials.widgets', ['text' => 'Projects', 'value' => $data->get('active_projects_count'), 'icon' => 'fas fa-clock', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
            @include('dashboard.partials.widgets', ['text' => 'Tasks', 'value' => $data->get('active_tasks_count'), 'icon' => 'fas fa-tasks', 'colour' => 'lightblue color-palette', 'link' => route('tasks.index')])
            @include('dashboard.partials.widgets', ['text' => 'Tickets', 'value' => $data->get('active_tickets_count'), 'icon' => 'fas fa-life-ring', 'colour' => 'lightblue color-palette', 'link' => route('tickets.index')])
            @include('dashboard.partials.widgets', ['text' => 'NaN', 'value' => 'NaN', 'icon' => 'fas fa-times', 'colour' => 'danger color-palette', 'link' => null])
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                Today summary
                <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('today_summary')->count() }}</span>
            </div>
            <div class="card-body">
                <!-- Content -->
                <div class="table-responsive">
                    <table id="@if($data->get('today_summary')->count() > 0){{ 'today-summary-table' }}@endif" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Due date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data->get('today_summary') as $item)
                                <tr>
                                    <td><a href="{{ $item->get('url') }}">{{ $item->get('name') }}</a></td>
                                    <td>@include('dashboard.partials.type', ['type' => $item->get('type')]){{ __('pages.title.' . $item->get('type')) }}</td>
                                    <td>{{ $item->get('due_date')->format('d.m.Y') }}</td>
                                    <td><a href="{{ $item->get('url') }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">You are free for today!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
    <!-- Custom -->
    <script>
        $(function () {
            $('#today-summary-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush