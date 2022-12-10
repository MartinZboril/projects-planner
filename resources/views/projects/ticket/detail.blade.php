@extends('layouts.master', ['toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.tickets', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('projects.ticket.edit', ['project' => $project->id, 'ticket' => $ticket->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
            @include('tickets.partials.buttons', ['ticket' => $ticket, 'buttonSize' => 'sm', 'buttonText' => true])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Message -->
                @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                <!-- Content -->
                @include('tickets.partials.detail.information', ['ticket' => $ticket])                            
            </div>
        </section>
    </div>
@endsection