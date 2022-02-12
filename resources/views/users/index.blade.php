@extends('layouts.master')

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/toastr/toastr.css">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline rounded-0">
                <div class="card-body">
                    <input type="hidden" id="userform-message" value="{{ Session::get('message') }}">
                    <input type="hidden" id="userform-message-type" value="{{ Session::get('type') }}">

                    <div class="table-responsive">
                        <table id="users-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Job title</th>
                            <th>Mobile</th>
                            <th>City</th>
                            <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }} {{ $user->surname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->job_title }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->city }}</td>
                                        <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>  
                    </div>
                </div>
            </div>            
        </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="../plugins/toastr/toastr.min.js"></script>

    <script>
        $(function () {
            $("#users-table").DataTable();

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