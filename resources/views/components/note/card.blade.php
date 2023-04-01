<div class="row">
    @forelse ($notes as $note)
        <div class="col-md-3">
            <x-note.item :$note :$redirect />
        </div>
    @empty
        No notes were found!
    @endforelse
</div>