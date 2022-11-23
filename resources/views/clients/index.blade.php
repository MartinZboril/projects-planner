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
                <div class="card-body">
                    <!-- Message -->
                    @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="@if($clients->count() > 0){{ 'clients-table' }}@endif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact Person</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                    <tr>
                                        <td><a href="{{ route('clients.detail', $client->id) }}">{{ $client->name }}</a></td>
                                        <td>{{ $client->contact_person_label }}</td>
                                        <td>{{ $client->email_label }}</td>
                                        <td>{{ $client->created_at->format('d.m.Y') }}</td>
                                        <td>
                                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                                            <a href="{{ route('clients.detail', $client->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No clients were found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>  
                    </div>
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