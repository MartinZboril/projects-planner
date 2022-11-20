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
        @include('dashboard.partials.action', ['active' => 'tasks'])
    </div>
    <!-- Main content -->
    <section class="content">
        <!-- Message -->
        @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
        <div class="row">
            @include('dashboard.partials.widgets', ['text' => 'Today', 'value' => $data->get('today_tasks_count'), 'icon' => 'fas fa-calendar-day', 'colour' => 'lightblue color-palette', 'link' => route('tasks.index')])
            @include('dashboard.partials.widgets', ['text' => 'This week', 'value' => $data->get('this_week_tasks_count'), 'icon' => 'fas fa-calendar-week', 'colour' => 'lightblue color-palette', 'link' => route('tasks.index')])
            @include('dashboard.partials.widgets', ['text' => 'This month', 'value' => $data->get('month_tasks_count'), 'icon' => 'fas fa-calendar', 'colour' => 'lightblue color-palette', 'link' => route('tasks.index')])
            @include('dashboard.partials.widgets', ['text' => 'Last month', 'value' => $data->get('last_month_tasks_count'), 'icon' => 'far fa-calendar', 'colour' => 'lightblue color-palette', 'link' => route('tasks.index')])
            @include('dashboard.partials.widgets', ['text' => 'Active', 'value' => $data->get('active_tasks_count'), 'icon' => 'fas fa-play', 'colour' => 'info', 'link' => route('reports.tasks')])
            @include('dashboard.partials.widgets', ['text' => 'Pause', 'value' => $data->get('pause_tasks_count'), 'icon' => 'fas fa-stop', 'colour' => 'warning', 'link' => route('reports.tasks')])
            @include('dashboard.partials.widgets', ['text' => 'Overdue', 'value' => $data->get('overdue_tasks_count'), 'icon' => 'fas fa-exclamation-circle', 'colour' => 'danger', 'link' => route('reports.tasks')])
            @include('dashboard.partials.widgets', ['text' => 'Total', 'value' => $data->get('total_tasks_count'), 'icon' => 'fas fa-tasks', 'colour' => 'primary', 'link' => route('reports.tasks')])
        </div>
        @if($data->get('overdue_todos')->count() > 0)
            <div class="card card-primary card-outline">
                <div class="card-header">
                    Overdue ToDos
                    <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('overdue_todos')->count() }}</span>
                </div>
                <div class="card-body">
                    <!-- Content -->
                    @include('todos.partials.list', ['todos' => $data->get('overdue_todos'), 'project' => null, 'redirect' => 'dashboard_task', 'action' => 1])            
                </div>
            </div>
        @endif
        @if($data->get('overdue_tasks')->count() > 0)
            <div class="card card-primary card-outline">
                <div class="card-header">
                    Overdue Tasks
                    <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('overdue_tasks')->count() }}</span>
                </div>
                <div class="card-body">
                    <!-- Content -->
                    @include('tasks.partials.table', ['id' => 'overdue-tasks-table', 'tasks' => $data->get('overdue_tasks'), 'display' => ['project'], 'redirect' => 'task'])
                </div>
            </div>
        @endif
        @if($data->get('new_tasks')->count() > 0)
        <div class="card card-primary card-outline">
            <div class="card-header">
                New Tasks
                <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('new_tasks')->count() }}</span>
            </div>
            <div class="card-body">
                <!-- Content -->
                @include('tasks.partials.table', ['id' => 'new-tasks-table', 'tasks' => $data->get('new_tasks'), 'display' => ['project'], 'redirect' => 'task'])
            </div>
        </div>  
        @endif
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">Task Statuses</div>
                    <div class="card-body" style="height: 400px">
                        <canvas id="task-statuses-chart" style="w-100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">{{ now()->format('Y') }} – Yearly Overview</div>
                    <div class="card-body" style="height: 400px">
                        <canvas id="yearly-overview-chart" class="w-100"></canvas>
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
        new Chart("task-statuses-chart", {
            type: "doughnut",
            data: {
                labels: ['New', 'In progress', 'Complete'],
                datasets: [{
                    backgroundColor: ['#17a2b8', '#ffc107', '#28a745'],
                    data: [{{ $data->get('new_tasks_count') }}, {{ $data->get('in_progress_tasks_count') }}, {{ $data->get('complete_tasks_count') }}]
                }]
            },
            options: {
                title: {
                    display: true,
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart("yearly-overview-chart", {
            type: "line",
            data: {
                labels: @json($data->get('report')->get('report_months')),
                datasets: [{ 
                    data: @json($data->get('report')->get('total_tasks_by_month')),
                    borderColor: '#007bff',
                    fill: false,
                    label: 'Total'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                    scales: {
                    y: {
                        min: 0,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                }
            },
        });

        $(function () {
            $('#new-tasks-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('#overdue-tasks-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush