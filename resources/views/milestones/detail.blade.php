@extends('layouts.master')

@section('styles')
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="p-3 rounded-0 mb-3" style="background-color:white;">
        <a href="{{ route('projects.milestones', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        <a href="{{ route('milestones.edit', ['project' => $project->id, 'milestone' => $milestone->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" id="milestoneform-message" value="{{ Session::get('message') }}">
            <input type="hidden" id="milestoneform-message-type" value="{{ Session::get('type') }}">

            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary card-outline rounded-0">
                        <div class="card-header">{{ $milestone->name }}</div>
                        <div class="card-body">
                            <span class="d-block">Project: <b>{{ $milestone->project->name }}</b></span>
                            <span class="d-block">User: <b>{{ $milestone->owner->name }} {{ $milestone->owner->surname }}</b></span>
                            <span class="d-block">Start date: <b>{{ $milestone->start_date->format('d.m.Y') }}</b></span>
                            <span class="d-block">End date: <b>{{ $milestone->end_date->format('d.m.Y') }}</b></span>
                            <span class="d-block">Colour: <b><span style="color: {{ $milestone->colour }}">{{ $milestone->colour }}</span></b></span>
                            <hr>
                            {!! $milestone->description !!}
                        </div>
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
            if($('#milestoneform-message').val()) {
                if($('#milestoneform-message-type').val() == "success") {
                    toastr.success($('#milestoneform-message').val());
                } else if($('#milestoneform-message-type').val() == "info") {
                    toastr.info($('#milestoneform-message').val());
                } else {
                    toastr.error($('#milestoneform-message').val());            
                }
            }; 
        });
    </script>
@endsection