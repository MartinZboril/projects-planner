@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('projects.tickets.update', ['project' => $ticket->project, 'ticket' => $ticket]) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <x-ticket.fields :$ticket type="edit" :project="$ticket->project" />
                </form>  
            </div>
        </section>
    </div>
@endsection