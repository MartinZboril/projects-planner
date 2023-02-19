@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'summernote' => true])

@section('title', __('pages.title.milestone'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <x-milestone.ui.actions :$milestone />
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Message -->
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <!-- Content -->
                <div class="row">
                    <div class="col-md-5">
                        @include('projects.milestones.partials.informations')
                        <x-file.card :upload-form-route="route('projects.milestones.files.upload', ['project' => $milestone->project, 'milestone' => $milestone])" :files="$milestone->files" />
                        <x-activity-feed.card />
                    </div>
                    <div class="col-md-7">
                        <div class="card card-primary card-outline">
                            <div class="card-header">Tasks</div>
                            <div class="card-body">
                                <x-task.table table-id="tasks-table" :tasks="$milestone->tasks" type="projects" />
                            </div>
                        </div>
                        <x-comment.card :comments="$milestone->comments" :parent="['project' => $milestone->project, 'milestone' => $milestone]" :store-form-route="route('projects.milestones.comments.store', ['project' => $milestone->project, 'milestone' => $milestone])" update-form-route-name="projects.milestones.comments.update" /> 
                    </div>
                </div>         
            </div>
        </section>
    </div>
@endsection