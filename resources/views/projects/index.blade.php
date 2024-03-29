@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'project' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
            <a href="{{ route('reports.projects') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-chart-line mr-1"></i>Report</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Projects
                    </div>
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
        $('#projects-table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>       
@endpush