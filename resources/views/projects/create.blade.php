@extends('layouts.master', ['select2' => true, 'summernote' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('projects.store') }}" method="post">
                    @csrf
                    @method('POST')
                    @include('projects.forms.fields', ['project' => $project, 'type' => 'create'])  
                </form>     
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#client-id').select2({
                theme: 'bootstrap4',
                placeholder: 'select client'
            });

            $('#team').select2({
                theme: 'bootstrap4',
                placeholder: 'select member'
            });

            $('#description').summernote();
        });
    </script>
@endpush