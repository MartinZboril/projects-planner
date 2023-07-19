@extends('layouts.master', ['summernote' => true, 'toaster' => true, 'ticket' => true, 'comment' => true, 'file' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.tickets.index', $ticket->project) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('projects.tickets.edit', ['project' => $ticket->project, 'ticket' => $ticket]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('tickets.partials.buttons', ['buttonSize' => 'sm', 'type' => 'detail', 'tableIdentifier' => '', 'redirect' => route('projects.tickets.index', $ticket->project)])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <div class="col-md-5">
                        @include('tickets.partials.informations')
                        <x-activity-feed.card :activities="$ticket->activities" />
                    </div>
                    <div class="col-md-7">
                        <x-file.card :upload-form-route="route('projects.tickets.files.upload', ['project' => $ticket->project, 'ticket' => $ticket])" :parent="['project' => $ticket->project, 'ticket' => $ticket]" destroy-form-route-name="tickets.files.destroy" :files="$ticket->files" />
                        <x-comment.card :comments="$ticket->comments" :parent="['project' => $ticket->project, 'ticket' => $ticket]" :store-form-route="route('projects.tickets.comments.store', ['project' => $ticket->project, 'ticket' => $ticket])" update-form-route-name="projects.tickets.comments.update" destroy-form-route-name="projects.tickets.comments.destroy" />
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
