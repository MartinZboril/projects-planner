<div class="card card-{{ $note->is_private ? 'gray' : 'warning'}}">
    <div class="card-header">
        <h3 class="card-title">{{ $note->name }}</h3>
        <div class="card-tools">
            <a href="{{ $editRoute }}" class="btn btn-tool">
                <i class="fas fa-edit"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        {!! $note->content !!}
    </div>
</div>