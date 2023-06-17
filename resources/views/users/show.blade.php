@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.user'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                <div class="row">
                    <div class="col-md-4">
                        @include('users.partials.profile')
                    </div>
                    <div class="col-md-8">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="card-title">Rates</div>
                                <div class="card-tools">
                                    <a href="{{ route('users.rates.assignment', $user) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-user-plus mr-1"></i>Assign</a>
                                </div>                               
                            </div>
                            <div class="card-body">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                        <x-activity-feed.card />
                    </div>
                </div>         
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush