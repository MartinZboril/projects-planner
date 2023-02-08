@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.ticket'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('tickets.update', $ticket) }}" method="post">
                    @csrf
                    @method('PATCH')
                    @include('tickets.forms.fields', ['ticket' => $ticket, 'type' => 'edit'])
                </form>  
            </div>
        </section>
    </div>
@endsection

@push('scripts')
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