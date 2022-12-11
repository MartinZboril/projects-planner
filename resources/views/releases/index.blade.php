@extends('layouts.master')

@section('title', __('pages.title.report'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">Releases</div>
                    <div class="card-body">
                        <!-- Content -->
                        @if ($releases->get('releases')->count() > 0)
                            <div class="timeline">
                                @foreach ($releases->get('releases')->sortByDesc('realese_date') as $key => $release)
                                    @include('releases.partials.release', ['release' => $release])
                                @endforeach
                            </div>                            
                        @else
                            No releases were found!
                        @endif
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection