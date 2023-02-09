@extends('layouts.master', ['summernote' => true])

@section('title', __('pages.title.client'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('clients.notes.index', ['client' => $client->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Form -->
                <form action="{{ route('clients.notes.update', ['client' => $client, 'note' => $note]) }}" method="post">
                    @csrf
                    @method('PATCH')
                    @include('clients.notes.forms.fields', ['client' => $client, 'note' => $note, 'type' => 'edit'])
                </form>        
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