@extends('layouts.master', ['datatables' => true, 'toaster' => true])

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
                <div class="card-body">
                    <!-- Message -->
                    @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                    <!-- Content -->
                    <div class="table-responsive">
                        <table id="@if($users->count() > 0){{ 'users-table' }}@endif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Job title</th>
                                    <th>Mobile</th>
                                    <th>City</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td><a href="{{ route('users.detail', $user->id) }}">{{ $user->full_name }}</a></td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->job_title }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->city }}</td>
                                        <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                                            <a href="{{ route('users.detail', $user->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No users were found!</td>
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
            $("#users-table").DataTable();
        });
    </script>
@endpush