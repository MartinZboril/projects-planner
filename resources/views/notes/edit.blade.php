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
                <form action="{{ route('notes.update', $note) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <x-note.fields :$note type="edit" />
                </form>      
            </div>
        </section>
    </div>
@endsection