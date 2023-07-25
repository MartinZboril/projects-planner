@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'icheck' => true, 'chartJS' => true, 'todo' => true, 'doughnut' => true, 'overview' => true, 'task' => true])

@section('title', __('pages.title.dashboard'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('dashboard.partials.action', ['active' => 'tasks'])
        </div>
        <!-- Main content -->
        <section class="content">
            <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
            <div class="row">
                <x-dashboard.widget text="Today" :value="$data->get('today_tasks_count')" icon="fas fa-calendar-day" colour="lightblue color-palette" :link="route('tasks.index')" />
                <x-dashboard.widget text="This week" :value="$data->get('this_week_tasks_count')" icon="fas fa-calendar-week" colour="lightblue color-palette" :link="route('tasks.index')" />
                <x-dashboard.widget text="This month" :value="$data->get('month_tasks_count')" icon="fas fa-calendar" colour="lightblue color-palette" :link="route('tasks.index')" />
                <x-dashboard.widget text="Last month" :value="$data->get('last_month_tasks_count')" icon="far fa-calendar" colour="lightblue color-palette" :link="route('tasks.index')" />
                <x-dashboard.widget text="Active" :value="$data->get('active_tasks_count')" icon="fas fa-play" colour="info" :link="route('reports.tasks')" />
                <x-dashboard.widget text="Pause" :value="$data->get('pause_tasks_count')" icon="fas fa-stop" colour="warning" :link="route('reports.tasks')" />
                <x-dashboard.widget text="Overdue" :value="$data->get('overdue_tasks_count')" icon="fas fa-exclamation-circle" colour="danger" :link="route('reports.tasks')" />
                <x-dashboard.widget text="Total" :value="$data->get('total_tasks_count')" icon="fas fa-tasks" colour="primary" :link="route('reports.tasks')" />
            </div>
            @if($data->get('overdue_todos')->count() > 0)
                <x-dashboard.listing :items="$data->get('overdue_todos')" title="Overdue Todos" type="todo" />
            @endif
            @if($data->get('overdue_tasks')->count() > 0)
                <x-dashboard.listing :items="$data->get('overdue_tasks')" title="Overdue Tasks" type="task" />
            @endif
            @if($data->get('new_tasks')->count() > 0)
                <div class="card card-primary card-outline" id="newed-tasks-card">
                    <div class="card-header">
                        New Tasks
                        <span class="badge badge-primary ml-2" style="font-size:14px;" id="newed-task-items-count-list">{{ $data->get('new_tasks')->count() }}</span>
                    </div>
                    <div class="card-body">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <x-chart.doughnut title="Task Statuses" chart-id="task-statuses-chart" :labels="['New', 'In progress', 'Complete']" :colours="['#17a2b8', '#ffc107', '#28a745']" :data="[$data->get('new_tasks_count'), $data->get('in_progress_tasks_count'), $data->get('complete_tasks_count')]" />
                </div>
                <div class="col-md-8">
                    <x-chart.overview :report-months="$data->get('report')->get('report_months')" :total-count="$data->get('report')->get('total_tasks_by_month')" :year="now()->format('Y')" chart-id="yearly-overview-chart" />
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $('#tasks-table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
