@extends('layouts.master', ['toaster' => true, 'summernote' => true, 'ticket' => true, 'comment' => true])

@section('title', __('pages.title.ticket'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('tickets.partials.buttons', ['buttonSize' => 'sm', 'type' => 'detail', 'tableIdentifier' => ''])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <div class="col-md-5">
                        @include('tickets.partials.informations')            
                        <x-activity-feed.card />
                    </div>
                    <div class="col-md-7">
                        <x-file.card :upload-form-route="route('tickets.files.upload', $ticket)" :files="$ticket->files" />
                        <x-comment.card :comments="$ticket->comments" :parent="[$ticket]" :store-form-route="route('tickets.comments.store', $ticket)" update-form-route-name="tickets.comments.update" /> 
                    </div>
                </div> 
            </div>
        </section>
    </div>
@endsection