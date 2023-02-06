@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'summernote' => true])

@section('title', __('pages.title.milestone'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('milestones.partials.action', ['project' => $project, 'milestone' => $milestone])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Message -->
                @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                <!-- Content -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                {{ $milestone->name }}
                                <span class="badge badge-{{ $milestone->progress == 1 ? 'success' : 'warning' }} ml-1" style="font-size:14px;">{{ $milestone->progress == 1 ? 'Completed' : 'In progress' }}</span>
                                @if($milestone->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
                            </div>
                            <div class="card-body">
                                <span class="d-block">Project: <b><a href="{{ route('projects.detail', $milestone->project->id) }}">{{ $milestone->project->name }}</a></b></span>
                                <span class="d-block">User: <b><a href="{{ route('users.show', $milestone->owner) }}">{{ $milestone->owner->full_name }}</a></b></span>
                                <span class="d-block">Start date: <b>{{ $milestone->start_date->format('d.m.Y') }}</b></span>
                                <span class="d-block">End date: <b>{{ $milestone->due_date->format('d.m.Y') }}</b></span>
                                <span class="d-block">Tasks: <b>{{ $milestone->tasksCompleted->count() }}/{{ $milestone->tasks->count() }}</b><span class="badge badge-{{ $milestone->progress == 1 ? 'success' : 'warning' }} ml-1">{{ $milestone->progress * 100 }} % Complete</span></span>
                                <hr>
                                {!! $milestone->description !!}
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Files</div>
                            <div class="card-body">
                                @include('files.list', ['files' => $milestone->files, 'parentId' => $milestone->id, 'parentType' => 'milestone'])
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Activity Feed</div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card card-primary card-outline">
                            <div class="card-header">Tasks</div>
                            <div class="card-body">
                                @include('tasks.partials.table', ['id' => 'tasks-table', 'tasks' => $milestone->tasks, 'display' => [], 'redirect' => 'projects'])       
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">Comments</div>
                            <div class="card-body">
                                @include('comments.list', ['comment' => $comment, 'comments' => $milestone->comments, 'parentId' => $milestone->id, 'parentType' => 'milestone'])
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
        $(function () {
            $("#tasks-table").DataTable();
        });
    </script>
@endpush