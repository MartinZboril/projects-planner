@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="taskform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="taskform-message-type" value="{{ Session::get('type') }}">

            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">

                    </div>
                </div>
                <div class="col-md-7">
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
            if($('#taskform-message').val()) {
                if($('#taskform-message-type').val() == "success") {
                    toastr.success($('#taskform-message').val());
                } else if($('#taskform-message-type').val() == "info") {
                    toastr.info($('#taskform-message').val());
                } else {
                    toastr.error($('#taskform-message').val());            
                }
            }; 
        });
    </script>
@endsection