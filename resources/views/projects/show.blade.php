@extends('layouts.master', ['toaster' => true, 'progressbar' => true, 'project' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.actions')
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'project'])
                </div>
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <div class="col-md-4">
                        @include('projects.partials.informations')
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <x-chart.progress-bar headline="Budget" :title="$project->amount" :title-amount="true" :subtitle="$project->budget" :subtitle-amount="true" chart-id="budget-progress-bar" :value="$project->budget_plan >= 100 ? '1.0' : ($project->budget_plan / 100)" :text="$project->budget_plan . '%'" :colour="'#' . ($project->budget_plan > 100 ? 'dc3545' : '28a745')" />
                            </div>
                            <div class="col-md-4">
                                <x-chart.progress-bar headline="Time Plan" :title="$project->total_time . ' Hours'" :subtitle="'Est. Hours: ' . $project->estimated_hours . ' Hours'" chart-id="plan-progress-bar" :value="$project->time_plan >= 100 ? '1.0' : ($project->time_plan / 100)" :text="$project->time_plan . '%'" :colour="'#' . ($project->time_plan > 100 ? 'dc3545' : '28a745')" />
                            </div>
                            <div class="col-md-4">
                                <x-chart.progress-bar headline="Tasks" :title="$project->pending_tasks_count . ' Pending'" :subtitle="$project->done_tasks_count . ' Done'" chart-id="tasks-progress-bar" :value="$project->tasks_plan >= 100 ? '1.0' : ($project->tasks_plan / 100)" :text="$project->tasks_plan . '%'" :colour="'#' . ($project->tasks_plan > 100 ? 'dc3545' : '28a745')" />
                            </div>
                        </div>
                        <x-activity-feed.card />
                    </div>
                </div>  
            </div>
        </section>
    </div>
@endsection