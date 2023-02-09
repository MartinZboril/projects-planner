@extends('layouts.master', ['toaster' => true])

@section('title', __('pages.title.note'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            <a href="{{ route('notes.create') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus mr-1"></i>Create</a>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        Notes
                    </div>
                    <div class="card-body">
                        <!-- Message -->
                        @include('site.partials.message', ['message' => Session::get('message'), 'type' => Session::get('type')])
                        <!-- Content -->
                        <div class="row">
                            @forelse ($notes as $note)
                                <div class="col-md-3">
                                    @include('site.notes.card', ['note' => $note, 'editRoute' => route('notes.edit', ['note' => $note])])
                                    @include('notes.forms.mark', ['note' => $note, 'id' => ($note->is_marked ? 'unmark' : 'mark') . '-note-' . $note->id . '-form'])
                                </div>
                            @empty
                                No notes were found!
                            @endforelse
                        </div>
                    </div>
                </div>            
            </div>
        </section>
    </div>
@endsection