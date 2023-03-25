@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'chartJS' => true, 'doughnut' => true, 'overview' => true])

@section('title', __('pages.title.dashboard'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('dashboard.partials.action', ['active' => 'projects'])
        </div>
        <!-- Main content -->
        <section class="content">
            <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
            <div class="row">
                <x-dashboard.widget text="Today" :value="$data->get('today_timers_total_time_sum') . ' Hours'" icon="fas fa-calendar-day" colour="lightblue color-palette" :link="route('projects.index')" />
                <x-dashboard.widget text="This week" :value="$data->get('this_week_timers_total_time_sum') . ' Hours'" icon="fas fa-calendar-week" colour="lightblue color-palette" :link="route('projects.index')" />
                <x-dashboard.widget text="Average" :value="$data->get('spent_time_avg') . ' Hours'" icon="fas fa-balance-scale" colour="lightblue color-palette" :link="route('projects.index')" />
                <x-dashboard.widget text="Budget" :value="$data->get('budget_avg') . ' %'" icon="fas fa-percent" :colour="$data->get('budget_avg') > 100 ? 'danger' : 'lightblue' . ' color-palette'" :link="route('projects.index')" />
                <x-dashboard.widget text="Active" :value="$data->get('active_work_projects_count')" icon="fas fa-play" colour="info" :link="route('reports.projects')" />
                <x-dashboard.widget text="Done" :value="$data->get('done_projects_count')" icon="fas fa-check" colour="success" :link="route('reports.projects')" />
                <x-dashboard.widget text="Overdue" :value="$data->get('overdue_projects_count')" icon="fas fa-exclamation-circle" colour="danger" :link="route('reports.projects')" />
                <x-dashboard.widget text="Total" :value="$data->get('total_projects_count')" icon="fas fa-clock" colour="primary" :link="route('reports.projects')" />
            </div>
            @if($data->get('overdue_projects')->count() > 0)
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Overdue Projects
                        <span class="badge badge-primary ml-2" style="font-size:14px;">{{ $data->get('overdue_projects')->count() }}</span>
                    </div>
                    <div class="card-body">
                        <x-project.table table-id="overdue-projects-table" :overdue="true" />
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
                        <x-milestone.table table-id="overdue-milestones-table" />
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <x-chart.doughnut title="Project Statuses" chart-id="project-statuses-chart" :labels="['Actived', 'Finished', 'Archived']" :colours="['#17a2b8', '#28a745', '#007bff']" :data="[$data->get('active_projects_count'), $data->get('finish_projects_count'), $data->get('archive_projects_count')]" />
                </div>
                <div class="col-md-8">
                    <x-chart.overview :report-months="$data->get('report')->get('report_months')" :total-count="$data->get('report')->get('total_projects_by_month')" :year="now()->format('Y')" chart-id="yearly-overview-chart" />
                </div>
            </div>               
        </section>
    </div>
@endsection