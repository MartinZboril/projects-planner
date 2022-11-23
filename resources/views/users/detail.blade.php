@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.user'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 mb-3" style="background-color:white;">
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Message -->
            @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
            <!-- Content -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">Profile</div>
                        <div class="card-body">
                            <div class="text-center">
                                <h5>{{ $user->full_name }}</h5>
                                <img src="{{ asset('dist/img/user.png') }}" class="img-circle mb-2" alt="User Image" style="width: 100px;height: 100px;">
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Email</td><td class="text-right"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Username</td><td class="text-right">{{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Job title</td><td class="text-right">{{ $user->job_title ? $user->job_title : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Mobile</td><td class="text-right">@if($user->mobile)<a href="tel:{{ $user->mobile }}">{{ $user->mobile }}</a>@else{{ '-' }}@endif</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Phone</td><td class="text-right">@if($user->phone)<a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>@else{{ '-' }}@endif</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Address</td><td class="text-right">{{ $user->street ? $user->street . ' ' . $user->house_number : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">City</td><td class="text-right">{{ $user->city ? $user->city : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">ZIP code</td><td class="text-right">{{ $user->zip_code ? $user->zip_code : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Country</td><td class="text-right">{{ $user->country ? $user->country : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Phone</td><td class="text-right">{{ $user->phone ? $user->phone : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Verified At</td><td class="text-right">{{ $user->email_verified_at ? $user->email_verified_at->format('d.m.Y H:i') : 'Not verified' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Created At</td><td class="text-right">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Updated</td><td class="text-right">{{ $user->updated_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">Rates</h5> 
                            <div class="card-tools">
                                <a href="{{ route('rates.create', ['user' => $user->id]) }}" class="bn btn-primary btn-sm ml-1"><i class="fas fa-plus mr-1"></i>Create</a>
                            </div>   
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="@if($user->rates->count() > 0){{ 'rates-table' }}@endif" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Rate</th>
                                            <th>Active</th>
                                            <th>Value</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($user->rates as $rate)
                                            <tr>
                                                <td><a href="{{ route('rates.edit', ['user' => $user->id, 'rate' => $rate->id]) }}">{{ $rate->name }}</a></td>
                                                <td>{{ $rate->is_active ? 'Yes' : 'No' }}</td>
                                                <td>{{ $rate->value }}</td>
                                                <td>                                                    
                                                    <a href="{{ route('rates.edit', ['user' => $user->id, 'rate' => $rate->id]) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">No rates were found!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>  
                            </div>        
                        </div>
                    </div>
                    <div class="card card-primary card-outline">
                        <div class="card-header">Activity Feed</div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>         
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $("#rates-table").DataTable();
        });
    </script>
@endpush