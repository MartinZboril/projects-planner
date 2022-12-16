@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.ticket'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
            <a href="{{ route('reports.tickets') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-chart-line mr-1"></i>Report</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Tickets
                    </div>
                    <div class="card-body">
                        <!-- Message -->
                        @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                        <!-- Content -->
                        @include('tickets.partials.table', ['id' => 'tickets-table', 'tickets' => $tickets, 'display' => ['project'], 'redirect' => 'tickets'])
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $("#tickets-table").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush