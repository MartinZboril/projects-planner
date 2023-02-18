@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <x-project.ui.actions :$project />
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    <x-project.ui.header :$project active="ticket" />
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-header"><a href="{{ route('projects.tickets.create', $project) }}" class="bn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Create</a></div>
                    <div class="card-body">
                        <!-- Message -->
                        <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                        <!-- Content -->
                        <x-ticket.table :tickets="$project->tickets" table-id="tickets-table" type="projects" />
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection