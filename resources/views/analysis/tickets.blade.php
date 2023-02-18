@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.analysis'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('reports.tickets') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <x-ticket.table :$tickets table-id="tickets-table" type="tickets" />
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection