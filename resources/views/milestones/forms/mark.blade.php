<form id="{{ $id }}" action="{{ route('milestones.mark', $milestone->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>