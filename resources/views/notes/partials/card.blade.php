<div class="card card-{{ $note->is_private ? 'gray' : 'warning'}}">
    <div class="card-header">
        <h3 class="card-title">{{ $note->name }}</h3>
        <div class="card-tools">
            <a href="{{ $editRoute }}" class="btn btn-tool">
                <i class="fas fa-edit"></i>
            </a>
            <a href="#" class="btn btn-tool" onclick="event.preventDefault(); document.getElementById('{{ ($note->is_marked ? 'unmark' : 'mark') . '-note-' . $note->id . '-form'}}').submit();">
                <i class="{{ ($note->is_marked ? 'fas' : 'far') }} fa-bookmark"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        {!! $note->content !!}
    </div>
</div>
@include('notes.forms.mark', ['id' => ($note->is_marked ? 'unmark' : 'mark') . '-note-' . $note->id . '-form', 'note' => $note, 'parentId' => $parentId, 'parentType' => $parentType])