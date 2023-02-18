<div class="row">
    @forelse ($notes as $note)
        <div class="col-md-3">
            <x-note.item :edit-form-route="route($editFormRouteName, [$parent, 'note' => $note])" :$note />
        </div>
    @empty
        No notes were found!
    @endforelse
</div>