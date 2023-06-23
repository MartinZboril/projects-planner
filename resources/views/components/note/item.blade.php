<div class="card card-{{ $note->is_private ? 'gray' : 'warning'}}">
    <div class="card-header">
        <h3 class="card-title">{{ $note->name }}</h3>
        <div class="card-tools">
            <a href="{{ $note->edit_route }}" class="btn btn-tool">
                <i class="fas fa-edit"></i>
            </a>
            <a href="#" class="btn btn-tool" onclick="markNote('{{ route('notes.mark', $note) }}', '{{ $redirect }}')">
                <i class="{{ ($note->is_marked ? 'fas' : 'far') }} fa-bookmark"></i>
            </a>
            <a href="#" class="btn btn-tool" onclick="deleteNote('{{ $note->destroy_route }}')"><i class="fas fa-trash mr-1"></i></a>
        </div>
    </div>
    <div class="card-body">
        {!! $note->content !!}
    </div>
</div>