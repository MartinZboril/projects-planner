@extends('layouts.master', ['datatables' => true, 'toaster' => true, 'user' => true])

@section('title', __('pages.title.user'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Users
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
@endpush