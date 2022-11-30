@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.analysis'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('reports.projects') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <!-- Content -->
                        @include('projects.partials.table', ['projects' => $projects])
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            var table = $("#projects-table").DataTable({
                lengthChange: false,
                buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
            });
            
            table.buttons().container().appendTo( '#projects-table_wrapper .col-md-6:eq(0)' );

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush