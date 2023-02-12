@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.client'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('clients.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Clients
                    </div>                    
                    <div class="card-body">
                        <!-- Message -->
                        <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                        <!-- Table -->
                        @include('clients.partials.table', ['clients' => $clients])
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $("#clients-table").DataTable();
        });
    </script>
@endpush