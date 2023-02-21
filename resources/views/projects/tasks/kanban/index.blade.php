@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.actions')
        </div>
        <!-- Main content -->
        <section class="content">
            <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
            <div class="container-fluid">
                <div class="card-header p-0 pb-2">
                    @include('projects.partials.header', ['active' => 'kanban'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <x-kanban.card :tasks="$project->newTasks" label="New" colour="info" />
                        </div>
                        <div class="col-md-4">
                            <x-kanban.card :tasks="$project->inProgressTasks" label="In Progress" colour="warning" />
                        </div>
                        <div class="col-md-4">
                            <x-kanban.card :tasks="$project->completedTasks" label="Completed" colour="success" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection