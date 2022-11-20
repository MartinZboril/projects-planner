@extends('layouts.master')

@section('title', __('pages.title.ticket'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="p-3 mb-3" style="background-color:white;">
        <a href="{{ route('tickets.detail', $ticket->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Form -->
            @include('tickets.forms.update', ['type' => 'ticket', 'projects' => $projects, 'redirect' => 'tickets'])
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- Custom -->
    <script>
        $(document).ready(function() {
            $('#project-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select project'
            });

            $('#milestone-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select milestone'
            });

            $('#assignee-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select assignee',
            });

            $('#status').select2({
                theme: 'bootstrap4',
                placeholder: 'select status'
            });

            $('#type').select2({
                theme: 'bootstrap4',
                placeholder: 'select type'
            });

            $('#priority').select2({
                theme: 'bootstrap4',
                placeholder: 'select priority'
            });

            $('#message').summernote();
        });
    </script>
@endpush