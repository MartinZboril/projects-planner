@extends('layouts.master')

@section('title', __('pages.title.ticket'))

@push('styles')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 mb-3" style="background-color:white;">
        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        @include('tickets.partials.detail.action', ['ticket' => $ticket])
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