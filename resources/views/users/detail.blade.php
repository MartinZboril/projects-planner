@extends('layouts.master')

@section('title', __('pages.title.user'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="message-content" value="{{ Session::get('message') }}">
            <input type="hidden" id="message-type" value="{{ Session::get('type') }}">

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
                        <div class="card-header">
                            <h5 class="card-title">Rates</h5> 
                            <div class="card-tools">
                                <a href="{{ route('rates.create', ['user' => $user->id]) }}" class="bn btn-primary btn-sm ml-1"><i class="fas fa-plus mr-1"></i>Create</a>
                            </div>   
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="{{ count($user->rates) > 0 ? 'rates-table' : '' }}" class="table table-bordered table-striped">
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
                                                <td>{{ $rate->name }}</td>
                                                <td>{{ $rate->is_active ? 'Yes' : 'No' }}</td>
                                                <td>{{ $rate->value }}</td>
                                                <td>                                                    
                                                    <a href="{{ route('rates.edit', ['user' => $user->id, 'rate' => $rate->id]) }}" class="btn btn-sm btn-dark" href=""><i class="fas fa-pencil-alt"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No rates were found!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>  
                            </div>        
                        </div>
                    </div>
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

@push('scripts')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js' ) }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <!-- Custom -->
    <script>
        $(function () {
            $("#rates-table").DataTable();
        });
    </script>
@endpush