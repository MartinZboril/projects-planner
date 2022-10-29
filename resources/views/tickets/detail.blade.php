@extends('layouts.master')

@section('title', __('pages.title.ticket'))

@push('styles')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        @if (!$ticket->is_convert && $ticket->assignee_id && $ticket->status != 2 && $ticket->status != 3)
            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('convert-ticket-to-task-form').submit();"><i class="fas fa-tasks mr-1"></i> Convert to task</a>
        @endif
        @if ($ticket->status == 1)
            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('close-ticket-form').submit();"><i class="fas fa-check mr-1"></i> Close</a>
        @elseif ($ticket->status == 2 || $ticket->status == 3)
            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('open-ticket-form').submit();"><i class="fas fa-bell mr-1"></i> Open</a>
        @endif
        @if ($ticket->status != 2 && $ticket->status != 3)
            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-ticket-form').submit();"><i class="fas fa-archive"></i></a>
        @endif
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Message -->
            @include('site.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
            <!-- Content -->
            @include('tickets.partials.information', ['ticket' => $ticket])                    
        </div>
    </section>
</div>

<!-- ticket status change forms -->
@include('tickets.forms.change', ['id' => 'open-ticket-form', 'ticket' => $ticket, 'status' => 1, 'redirect' => 'tickets'])    
@include('tickets.forms.change', ['id' => 'close-ticket-form', 'ticket' => $ticket, 'status' => 2, 'redirect' => 'tickets'])    
@include('tickets.forms.change', ['id' => 'archive-ticket-form', 'ticket' => $ticket, 'status' => 3, 'redirect' => 'tickets'])    
<!-- convert ticket to task form -->
@include('tickets.forms.convert', ['id' => 'convert-ticket-to-task-form', 'ticket' => $ticket, 'redirect' => 'tickets']) 

@endsection

@push('scripts')
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
@endpush