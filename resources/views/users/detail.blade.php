@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Edit</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="userform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="userform-message-type" value="{{ Session::get('type') }}">

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">Profile</div>
                        <div class="card-body">
                            <div class="text-center">
                                <h5>{{ $user->name }} {{ $user->surname }}</h5>
                                <img src="{{ asset('dist/img/user.png') }}" class="img-circle mb-2" alt="User Image" style="width: 100px;height: 100px;">
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Email</td><td class="text-right">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Username</td><td class="text-right">{{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Job title</td><td class="text-right">{{ $user->job_title ? $user->job_title : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Mobile</td><td class="text-right">{{ $user->mobile ? $user->mobile : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Phone</td><td class="text-right">{{ $user->phone ? $user->phone : '-' }}</td>
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
                    <div class="card card-primary card-outline rounded-0">
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

@section('scripts')
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>

    <script>
        $(function () {
            if($('#userform-message').val()) {
                if($('#userform-message-type').val() == "success") {
                    toastr.success($('#userform-message').val());
                } else if($('#userform-message-type').val() == "info") {
                    toastr.info($('#userform-message').val());
                } else {
                    toastr.error($('#userform-message').val());            
                }
            }; 
        });
    </script>
@endsection