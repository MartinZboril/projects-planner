@extends('layouts.master')

@section('title', __('pages.title.project'))

@push('styles')
    <!-- Toastr -->    
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        @include('projects.partials.action', ['project' => $project])
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card-header p-0 pb-2 mb-2">
                @include('projects.partials.header', ['active' => 'project'])
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Project <span class="badge badge-@include('projects.partials.colour', ['status' => $project->status]) ml-2" style='font-size:14px;'>@include('projects.partials.status', ['status' => $project->status])</span></div>
                        <div class="card-body">
                            <!-- Message -->
                            @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                            <!-- Content -->
                            <div class="text-muted">
                                <p class="text-sm">Name
                                    <b class="d-block ml-2">{{ $project->name }}</b>
                                </p>
                                <hr>
                                <p class="text-sm">Client
                                    <b class="d-block ml-2">{{ $project->client->name }}</b>
                                </p>
                                <hr>
                                <p class="text-sm">Information
                                    <span class="d-block ml-2">Start date: <b>{{ $project->start_date->format('d.m.Y') }}</b></span>
                                    <span class="d-block ml-2">Due date: <b>{{ $project->due_date->format('d.m.Y') }}</b></span>
                                    <span class="d-block ml-2">Deadline: <b>{{ $project->deadline }} day(s)</b></span>
                                </p>
                                <p class="text-sm">Cost
                                    <span class="d-block ml-2">Est. Hours: <b>{{ $project->estimated_hours }} Hours</b></span>
                                    <span class="d-block ml-2">Remaining Hours: <b>{{ $project->remaining_hours }} Hours ({{ $project->time_plan }} %)</b></span>
                                    <span class="d-block ml-2">Budget: <b>{{ number_format($project->budget, 2) }}</b></span>
                                    <span class="d-block ml-2">Remaining Budget: <b>{{ number_format($project->remaining_budget, 2) }} ({{ $project->budget_plan }} %)</b></span>
                                </p>
                                <hr>
                                <p class="text-sm">Team
                                    <ul class="list-group list-group-flush ml-1">
                                        @foreach ($project->team as $user)
                                            <li class="list-group-item">
                                                <div class="user-block">
                                                    @include('site.partials.user', ['user' => $user])
                                                    <span class="username">{{ $user->full_name }}</span>
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
                <div class="col-md-7">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Activity Feed</div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </section>
</div>

@include('projects.partials.forms', ['project' => $project])

@endsection

@push('scripts')
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
@endpush