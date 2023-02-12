@extends('layouts.master', ['datatables' => true, 'toaster' => true])

@section('title', __('pages.title.project'))

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="p-3 mb-3" style="background-color:white;">
            @include('projects.partials.action', ['project' => $project])
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card-header p-0 pb-2 mb-2">
                    @include('projects.partials.header', ['active' => 'note'])
                </div>          
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <a href="{{ route('projects.notes.create', ['project' => $project->id]) }}" class="btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Create</a>
                    </div>
                    <div class="card-body">
                        <!-- Message -->
                        <x-site.flash-messages :message="Session::get('message')" :type="Session::get('type')" />
                        <!-- Content -->
                        <div class="row">
                            @forelse ($project->notes as $note)
                                <div class="col-md-3">
                                    @include('site.notes.card', ['note' => $note, 'editRoute' => route('projects.notes.edit', ['project' => $project, 'note' => $note])])
                                    @include('projects.notes.forms.mark', ['project' => $project, 'note' => $note, 'id' => ($note->is_marked ? 'unmark' : 'mark') . '-note-' . $note->id . '-form'])
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