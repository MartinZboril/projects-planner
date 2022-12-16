@extends('layouts.master', ['summernote' => true])

@section('title', __('pages.title.note'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('notes.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                @include('notes.forms.store', ['form' => 'note', 'note' => $note, 'parentId' => null, 'parentType' => 'note'])    
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#content').summernote();
        });
    </script>
@endpush