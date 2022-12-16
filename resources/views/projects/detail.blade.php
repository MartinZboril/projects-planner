@extends('layouts.master', ['toaster' => true, 'progressbar' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.action', ['project' => $project])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'project'])
                </div>
                <!-- Message -->
                @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                <!-- Content -->                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                Project
                                <span class="badge badge-@include('projects.partials.colour', ['status' => $project->status]) ml-2" style='font-size:14px;'>@include('projects.partials.status', ['status' => $project->status])</span>
                                @if($project->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
                            </div>
                            <div class="card-body">
                                <div class="text-muted">
                                    <p class="text-sm">Name
                                        <b class="d-block ml-2">{{ $project->name }}</b>
                                    </p>
                                    <hr>
                                    <p class="text-sm">Client
                                        <b class="d-block ml-2"><a href="{{ route('clients.detail', $project->client->id) }}">{{ $project->client->name }}</a></b>
                                    </p>
                                    <hr>
                                    <p class="text-sm">Information
                                        <span class="d-block ml-2">Start date: <b>{{ $project->start_date->format('d.m.Y') }}</b></span>
                                        <span class="d-block ml-2">Due date: <b>{{ $project->due_date->format('d.m.Y') }}</b></span>
                                        <span class="d-block ml-2">Deadline: <span class="badge badge-{{ $project->deadline >= 0 ? 'success' : 'danger' }}">{{ $project->deadline }} day(s)</span>
                                    </span>
                                    </p>
                                    <p class="text-sm">Cost
                                        <span class="d-block ml-2">Est. Hours: <b>{{ $project->estimated_hours }} Hours</b></span>
                                        <span class="d-block ml-2">Remaining Hours: <b><span class="text-{{ $project->remaining_hours >= 0 ? 'sm' : 'danger' }}">{{ $project->remaining_hours }} Hours</span></b><span class="badge badge-{{ $project->time_plan > 100 ? 'danger' : 'success' }} ml-1">{{ $project->time_plan }} %</span></span>
                                        <span class="d-block ml-2">Budget: <b>@include('site.partials.amount', ['value' => $project->budget])</b></span>
                                        <span class="d-block ml-2">Remaining Budget: <b><span class="text-{{ $project->remaining_budget >= 0 ? 'sm' : 'danger' }}">@include('site.partials.amount', ['value' => $project->remaining_budget])</span></b><span class="badge badge-{{ $project->budget_plan > 100 ? 'danger' : 'success' }} ml-1">{{ $project->budget_plan }} %</span></span>
                                    </p>                            
                                    <hr>
                                    <p class="text-sm">Team
                                        <ul class="list-group list-group-flush ml-1">
                                            @foreach ($project->team as $user)
                                                <li class="list-group-item">
                                                    <div class="user-block">
                                                        @include('site.partials.user', ['user' => $user])
                                                        <span class="username"><a href="{{ route('users.detail', $user->id) }}">{{ $user->full_name }}</a></span>
                                                        <span class="description">Member</span>
                                                    </div> 
                                                </li>
                                            @endforeach
                                        </ul>
                                    </p>
                                    <hr>
                                    <p class="text-sm">Description
                                        <b class="d-block ml-2">{!! $project->description !!}</b>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">Budget</div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h6>@include('site.partials.amount', ['value' => $project->amount])</h6>
                                            <span class="text-muted">Budget: @include('site.partials.amount', ['value' => $project->budget])</span>
                                        </div>
                                        <div id="budget-progress-bar" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">Plan</div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h6>{{ $project->total_time }} Hours</h6>
                                            <span class="text-muted">Est. Hours: {{ $project->estimated_hours }} Hours</span>
                                        </div>
                                        <div id="plan-progress-bar" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">Tasks</div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h6>{{ $project->pending_tasks_count }} Pending</h6>
                                            <span class="text-muted">{{ $project->done_tasks_count }} Done</span>
                                        </div>
                                        <div id="tasks-progress-bar" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Activity Feed</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        createProgressBar('#budget-progress-bar', {{ $project->budget_plan >= 100 ? '1.0' : ($project->budget_plan / 100) }}, '{{ $project->budget_plan }} %', '#{{ $project->budget_plan > 100 ? "dc3545" : "28a745" }}');
        createProgressBar('#plan-progress-bar', {{ $project->time_plan >= 100 ? '1.0' : ($project->time_plan / 100) }}, '{{ $project->time_plan }} %', '#{{ $project->time_plan > 100 ? "dc3545" : "28a745" }}');
        createProgressBar('#tasks-progress-bar', {{ $project->tasks_plan >= 100 ? '1.0' : ($project->tasks_plan / 100) }}, '{{ $project->tasks_plan }} %', '#{{ $project->tasks_plan >= 100 ? "28a745" : "dc3545" }}');

        function createProgressBar(ident, value, text, color) {
            var budgetProgressBar = new ProgressBar.Circle(ident, {
                strokeWidth: 15,
                color: color,
                trailColor: '#eee',
                trailWidth: 15,
                text: {
                    value: text,
                    style: {
                        color: color,
                        position: 'absolute',
                        left: '50%',
                        top: '50%',
                        padding: 0,
                        margin: 0,
                        fontSize: '1.5rem',
                        fontWeight: 'bold',
                        transform: {
                            prefix: true,
                            value: 'translate(-50%, -50%)',
                        },
                    },
                }
            });

            budgetProgressBar.animate(value);            
        }
    </script>
@endpush