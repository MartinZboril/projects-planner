@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'chartJS' => true])

@section('title', __('pages.title.dashboard'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('dashboard.partials.action', ['active' => 'projects'])
        </div>
        <!-- Main content -->
        <section class="content">
            <!-- Message -->
            @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
            <div class="row">
                @include('dashboard.partials.widget', ['text' => 'Today', 'value' => $data->get('today_timers_total_time_sum') . ' Hours', 'icon' => 'fas fa-calendar-day', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
                @include('dashboard.partials.widget', ['text' => 'This week', 'value' => $data->get('this_week_timers_total_time_sum') . ' Hours', 'icon' => 'fas fa-calendar-week', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
                @include('dashboard.partials.widget', ['text' => 'Average', 'value' => $data->get('spent_time_avg') . ' Hours', 'icon' => 'fas fa-balance-scale', 'colour' => 'lightblue color-palette', 'link' => route('projects.index')])
                @include('dashboard.partials.widget', ['text' => 'Budget', 'value' => $data->get('budget_avg') . ' %', 'icon' => 'fas fa-percent', 'colour' => $data->get('budget_avg') > 100 ? 'danger' : 'lightblue' . ' color-palette', 'link' => route('projects.index')])
                @include('dashboard.partials.widget', ['text' => 'Active', 'value' => $data->get('active_work_projects_count'), 'icon' => 'fas fa-play', 'colour' => 'info', 'link' => route('reports.projects')])
                @include('dashboard.partials.widget', ['text' => 'Done', 'value' => $data->get('done_projects_count'), 'icon' => 'fas fa-stop', 'colour' => 'warning', 'link' => route('reports.projects')])
                @include('dashboard.partials.widget', ['text' => 'Overdue', 'value' => $data->get('overdue_projects_count'), 'icon' => 'fas fa-exclamation-circle', 'colour' => 'danger', 'link' => route('reports.projects')])
                @include('dashboard.partials.widget', ['text' => 'Total', 'value' => $data->get('total_projects_count'), 'icon' => 'fas fa-clock', 'colour' => 'primary', 'link' => route('reports.projects')])
            </div>
            @if($data->get('overdue_projects')->count() > 0)
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Overdue Projects
                        <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('overdue_projects')->count() }}</span>
                    </div>
                    <div class="card-body">
                        <!-- Content -->
                        @include('projects.partials.table', ['id' => 'overdue-projects-table', 'projects' => $data->get('overdue_projects')])
                    </div>
                </div>
            @endif
            @if($data->get('overdue_milestones')->count() > 0)
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Overdue Milestones
                        <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('overdue_milestones')->count() }}</span>
                    </div>
                    <div class="card-body">
                        <!-- Content -->
                        @include('milestones.partials.table', ['id' => 'overdue-milestones-table', 'milestones' => $data->get('overdue_milestones'), 'display' => []])
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">Project Statuses</div>
                        <div class="card-body" style="height: 400px">
                            <canvas id="project-statuses-chart" style="w-100"></canvas>
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
    <script>
        new Chart("project-statuses-chart", {
            type: "doughnut",
            data: {
                labels: ['Actived', 'Finished', 'Archived'],
                datasets: [{
                    backgroundColor: ['#17a2b8', '#28a745', '#007bff'],
                    data: [{{ $data->get('active_projects_count') }}, {{ $data->get('finish_projects_count') }}, {{ $data->get('archive_projects_count') }}]
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
                    data: @json($data->get('report')->get('total_projects_by_month')),
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
            $('#overdue-projects-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('#overdue-milestones-table').DataTable({
                'aLengthMenu': [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, 'All']],
                'iDisplayLength': 5
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush