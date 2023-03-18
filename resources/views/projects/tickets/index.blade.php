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
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'ticket'])
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-header"><a href="{{ route('projects.tickets.create', $project) }}" class="bn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Create</a></div>
                    <div class="card-body">
                        <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $('#tickets-table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>       
    <script src="{{ asset('js/actions/ticket.js') }}" defer></script>
@endpush